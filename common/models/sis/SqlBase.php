<?php

namespace common\models\sis;

class SqlBase extends SisSql
{
    use HasFilterUserLevel;

    public function getSelect() : array
    {
        $table = $this->getFilterUserLevel();

        if($table=='Client'){
            return [
                "Report.ClientId AS UserId",
                "Report.ClientDescription AS Usuario"
            ];
        }

        if($table=='Product'){
            return [
                'Report.GmidId AS UserId',
                'UPPER(Report.GmidDescription) AS Usuario'
            ];
        }

        return [
            sprintf("%sUserId AS UserId", $table),
            sprintf("%sUsuario AS Usuario", $table)
        ];
    }

    public function getInitialJoins()
    {
        return [
//            SisSqlJoin::inner('pm_dsm', 'DsmTam', 'DsmTam.DsmId = Dsm.UserId', false),
//            SisSqlJoin::inner('[user]', 'Tam', 'Tam.ParentId = DsmTam.DsmId', false),
//            SisSqlJoin::inner('client_seller', 'ClientSeller', 'ClientSeller.SellerId = Tam.UserId', false),
//            SisSqlJoin::inner('client', 'Client', 'ClientSeller.ClientId = Client.ClientId', false),
//            SisSqlJoin::inner('gmid', 'Gmid', '1=1', false),
//            SisSqlJoin::left('vw_gmid_ingredient', 'Ingredient', 'Ingredient.GmidId = Gmid.GmidId', false),
        ];
    }

    public function getFrom() : string
    {
        return 'FROM [sis_report] AS Report';
    }

    public function getHaving() : array
    {
        return [
        ];
    }


    public function getGroupBy(){
        $table = $this->getFilterUserLevel();

        if($table=='Client'){
            return "Report.ClientId,Report.ClientDescription";
        }

        if($table=='Product'){
            return "Report.GmidId,Report.GmidDescription";
        }

        return sprintf("%sUserId,%sUsuario", $table, $table);
    }
}