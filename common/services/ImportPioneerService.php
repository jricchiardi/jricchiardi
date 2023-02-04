<?php

namespace common\services;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use common\models\AuthAssignment;
use common\models\Client;
use common\models\ClientMarketing;
use common\models\ClientSeller;
use common\models\ClientType;
use common\models\Country;
use common\models\User;

class ImportPioneerService
{
    public static function getTrimmedUsername(string $username)
    {
        $trimmedUsername = str_replace(',', '', $username);
        $trimmedUsername = str_replace('ñ', 'n', $trimmedUsername);
        return str_replace(' ', '.', $trimmedUsername);
    }

    public static function importFromPathFile($pathFile)
    {
        $errors = [];

        $agencia = ClientType::findOne(['Description' => 'AGENCIA']);
        if (!$agencia) {
            $agencia = new ClientType;
            $agencia->Description = 'AGENCIA';
            if (!$agencia->save(false)) {
                $errors[] = [
                    'line' => '0',
                    'error' => 'ERROR AL INSERTAR AGENCIA',
                ];
                return $errors;
            }
        }

        $argentina = Country::findOne(['Description' => 'ARGENTINA']);
        if (!$argentina) {
            $errors[] = [
                'line' => '0',
                'error' => 'ERROR: NO SE ENCONTRO ARGENTINA',
            ];
            return $errors;
        }

        $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
        $reader->open($pathFile);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $n => $row) {
                if ($n === 1) {
                    continue;
                }
                $rowData = $row->toArray();
                $data = [
                    strval($rowData[0]), // dsm
                    strval($rowData[1]), // tam
                    intval($rowData[2]), // clientId
                    strval($rowData[3]), // client
                ];

                $dsm = trim($data[0]);
                $trimmedDsm = self::getTrimmedUsername($dsm);
                $newDsm = User::findByUsername($trimmedDsm);
                if (!$newDsm) {
                    $newDsm = new User;
                    $newDsm->Username = $trimmedDsm;
                    $newDsm->Email = $trimmedDsm . '@email.com';
                    $newDsm->Fullname = $dsm;
                    $newDsm->PasswordHash = '5f4dcc3b5aa765d61d8327deb882cf99';
                    if (!$newDsm->save()) {
                        $errors[] = [
                            'line' => $n,
                            'error' => "ERROR AL INSERTAR DSM {$trimmedDsm}",
                        ];
                        continue;
                    }
                    $newDsmAuthAssignment = new AuthAssignment;
                    $newDsmAuthAssignment->user_id = $newDsm->getId();
                    $newDsmAuthAssignment->item_name = 'DSM';
                    if (!$newDsmAuthAssignment->save()) {
                        $errors[] = [
                            'line' => $n,
                            'error' => "ERROR AL INSERTAR DSM AuthAssignment {$newDsm->getId()}",
                        ];
                        continue;
                    }
                }

                $tam = trim($data[1]);
                $trimmedTam = self::getTrimmedUsername($tam);
                $newTam = User::findByUsername($trimmedTam);
                if (!$newTam) {
                    $newTam = new User;
                    $newTam->Username = $trimmedTam;
                    $newTam->Email = $trimmedTam . '@email.com';
                    $newTam->Fullname = $tam;
                    $newTam->PasswordHash = '5f4dcc3b5aa765d61d8327deb882cf99';
                    $newTam->ParentId = $newDsm->getId();
                    if (!$newTam->save()) {
                        $errors[] = [
                            'line' => $n,
                            'error' => "ERROR AL INSERTAR TAM $trimmedTam",
                        ];
                        continue;
                    }
                    $newTamAuthAssignment = new AuthAssignment;
                    $newTamAuthAssignment->user_id = $newTam->getId();
                    $newTamAuthAssignment->item_name = 'SELLER';
                    if (!$newTamAuthAssignment->save()) {
                        $errors[] = [
                            'line' => $n,
                            'error' => "ERROR AL INSERTAR TAM AuthAssignment {$newTam->getId()}",
                        ];
                        continue;
                    }
                }

                $clientId = trim($data[2]);
                if (empty($clientId)) {
                    continue;
                }
                $client = trim($data[3]);

                $newClient = Client::findOne($clientId);
                if (!$newClient) {
                    $newClient = new Client;
                    $newClient->ClientId = $clientId;
                    $newClient->Description = $client;
                    $newClient->ClientTypeId = $agencia->ClientTypeId;
                    $newClient->CountryId = $argentina->CountryId;
                    if (!$newClient->save()) {
                        $errors[] = [
                            'line' => $n,
                            'error' => "ERROR AL INSERTAR CLIENTE $clientId",
                        ];
                        continue;
                    }

                    $newClientMarketing = ClientMarketing::findOne($clientId);
                    if (!$newClientMarketing) {
                        $newClientMarketing = new ClientMarketing;
                        $newClientMarketing->ClientMarketingId = $clientId;
                        $newClientMarketing->Description = $client;
                        $newClientMarketing->ClientTypeId = $agencia->ClientTypeId;
                        $newClientMarketing->CountryId = $argentina->CountryId;
                        if (!$newClientMarketing->save()) {
                            $errors[] = [
                                'line' => $n,
                                'error' => "ERROR AL INSERTAR CLIENTE DE MARKETING $clientId",
                            ];
                            continue;
                        }
                    }
                }

                $newClientSeller = ClientSeller::findOne(['ClientId' => $newClient->ClientId, 'SellerId' => $newTam->getId()]);
                if (!$newClientSeller) {
                    $newClientSeller = new ClientSeller;
                    $newClientSeller->ClientId = $newClient->ClientId;
                    $newClientSeller->SellerId = $newTam->getId();
                    if (!$newClientSeller->save()) {
                        $errors[] = [
                            'line' => $n,
                            'error' => "ERROR AL INSERTAR CLIENT SELLER (ClientId: $newClient->ClientId // SellerId: $newTam->getId()",
                        ];
                        continue;
                    }
                }
            }
        }

        $reader->close();

        return $errors;
    }
}
