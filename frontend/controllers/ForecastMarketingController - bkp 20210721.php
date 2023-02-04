<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\Campaign;
use common\models\ForecastMarketing;
use common\models\Setting;
use common\models\TypeAudit;
use Exception;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Protection;
use Yii;

/**
 * Site controller
 */
class ForecastMarketingController extends CustomController
{
    public function actionIndex()
    {
        return $this->render('index', []);
    }

    public function actionGetEmptyProducts()
    {
        Yii::$app->response->format = 'json';

        return [];
    }

    public function actionGetProducts($CountryId)
    {
        Yii::$app->response->format = 'json';

        return $this->getFilteredForecastProductsByPm(
            Yii::$app->user->identity->UserId,
            $CountryId
        );
    }

    /**
     * @throws Exception
     */
    public function actionGetForecast($CountryId, $TradeProductId = null, $GmidId = null)
    {
        Yii::$app->response->format = 'json';

        $pmId = Yii::$app->user->identity->UserId;

        // VERSION NUEVA
        $products = $this->getFilteredForecastProductsByPm($pmId, $CountryId);
        if (empty($products)) {
            return [];
        }

        $campaignId = Campaign::getActualCampaign()->CampaignId;

        // Escenario 1: Todos los forecast
        if (empty($TradeProductId) && empty($GmidId)) {
            return $this->getAllForecasts($pmId, $campaignId, $CountryId);
        }

        // Escenario 2: Un producto no semilla
        if (!empty($TradeProductId) && !empty($GmidId)) {
            return $this->getNoSeedProductForecasts($pmId, $campaignId, $CountryId, $TradeProductId, $GmidId);
        }

        // Escenario 3: Un producto semilla
        if (!empty($TradeProductId) && empty($GmidId)) {
            return $this->getSeedProductForecasts($pmId, $campaignId, $CountryId, $TradeProductId);
        }

        // Error:
        throw new Exception('Invalid scenario');
    }

    public function actionSave()
    {
        Yii::$app->response->format = 'json';

        $models = [];

        $modelsPost = Yii::$app->request->post('models', []);

        foreach ($modelsPost as $item) {
            $model = ForecastMarketing::findOne($item['ForecastMarketingId']);

            // custom setAttributes dynamic set values from actual month
            $model->_setAttributes($item);
            $model->save();

            $models[] = $model;
        }

        if (!empty($models)) {
            Yii::$app->auditcomponents->createAudit([
                'UserId' => Yii::$app->user->identity->UserId,
                'TypeAuditId' => TypeAudit::TYPE_SAVE_MARKETING_FORECAST,
            ]);
        }

        return $models;
    }

    public function actionExport()
    {
        $isLock = Yii::$app->utilcomponents->isMarketingForecastLocked();
        if ($isLock) {
            Yii::$app->session->setFlash('danger', Yii::t("app", "The forecast is locked!"));
            return $this->redirect(['index']);
        }

        $title = Yii::t("app", "Template Import Forecast") . "(" . date("Y-m-d") . ")";

        $month = date("m");
        $year = date("Y");
        $pmName = Yii::$app->user->identity->Fullname;
        $pmId = Yii::$app->user->identity->UserId;
        $campaignId = Campaign::getActualCampaign()->CampaignId;

        $connection = Yii::$app->db;

        $sql = "
SELECT *,
       'Q1'    = (result.January + result.February + result.March),
       'Q2'    = (result.April + result.May + result.June),
       'Q3'    = (result.July + result.August + result.September),
       'Q4'    = (result.October + result.November + result.December),
       'Total' = result.January + result.February + result.March + result.April + result.May + result.June +
                 result.July + result.August + result.September + result.October + result.November + result.December
FROM (SELECT            country.Description AS Pais,
                        u.UserId            AS DSMId,
                        u.Fullname          AS DSM,
                        pc.Description      AS PerformanceCenter,
                        tp.TradeProductId   AS TradeProductId,
                        tp.Description      AS TradeProduct,
                        g.GmidId            AS GmidId,
                        g.Description       AS Gmid,
          'January'   = CASE
                            WHEN 1 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.January, 0)
                            ELSE isnull(f.January, 0) END,
          'February'  = CASE
                            WHEN 2 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.February, 0)
                            ELSE isnull(f.February, 0) END,
          'March'     = CASE
                            WHEN 3 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.March, 0)
                            ELSE isnull(f.March, 0) END,
          'April'     = CASE
                            WHEN 4 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.April, 0)
                            ELSE isnull(f.April, 0) END,
          'May'       = CASE
                            WHEN 5 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.May, 0)
                            ELSE isnull(f.May, 0) END,
          'June'      = CASE
                            WHEN 6 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.June, 0)
                            ELSE isnull(f.June, 0) END,
          'July'      = CASE
                            WHEN 7 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.July, 0)
                            ELSE isnull(f.July, 0) END,
          'August'    = CASE
                            WHEN 8 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.August, 0)
                            ELSE isnull(f.August, 0) END,
          'September' =CASE
                           WHEN 9 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                               THEN isnull(sal.September, 0)
                           ELSE isnull(f.September, 0) END,
          'October'   = CASE
                            WHEN 10 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.October, 0)
                            ELSE isnull(f.October, 0) END,
          'November'  = CASE
                            WHEN 11 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.November, 0)
                            ELSE isnull(f.November, 0) END,
          'December'  = CASE
                            WHEN 12 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.December, 0)
                            ELSE isnull(f.December, 0) END
      FROM pm_product
               INNER JOIN gmid g on g.GmidId = pm_product.GmidId
               INNER JOIN trade_product tp ON tp.TradeProductId = g.TradeProductId
               INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
               INNER JOIN pm_dsm ON pm_dsm.PmId = pm_product.UserId
               INNER JOIN [user] u on u.UserId = pm_dsm.DsmId
               INNER JOIN (SELECT dsm.UserId AS DsmId, cm.CountryId AS DsmCountryId
                           FROM [user] dsm
                                    INNER JOIN auth_assignment aa on dsm.UserId = aa.user_id
                                    INNER JOIN [user] sellers ON sellers.ParentId = dsm.UserId
                                    INNER JOIN client_seller cs ON cs.SellerId = sellers.UserId
                                    INNER JOIN client_marketing cm ON cm.ClientMarketingId = cs.ClientId
                           WHERE item_name = 'DSM'
                             AND dsm.IsActive = 1
                           GROUP BY dsm.UserId, dsm.Fullname, cm.CountryId) dsmcountries
                          ON dsmcountries.DsmId = pm_dsm.DsmId AND dsmcountries.DsmCountryId = g.CountryId
               INNER JOIN country ON country.CountryId = dsmcountries.DsmCountryId
               LEFT JOIN forecast_marketing f
                         ON f.GmidId = pm_product.GmidId AND f.TradeProductId = pm_product.TradeProductId AND
                            f.DsmId = pm_dsm.DsmId
               LEFT JOIN (SELECT DsmId,
                                 sales.TradeProductId,
                                 sales.GmidId,
                                 January,
                                 February,
                                 March,
                                 Q1,
                                 April,
                                 May,
                                 June,
                                 Q2,
                                 July,
                                 August,
                                 September,
                                 Q3,
                                 October,
                                 November,
                                 December,
                                 Q4,
                                 Total
                          FROM SalesByDsmAndGmidAndTradeProduct sales
                                   INNER JOIN trade_product tp ON tp.TradeProductId = sales.TradeProductId
                                   INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                          WHERE pc.ValueCenterId <> 10111
                            AND sales.CampaignId = $campaignId) sal
                         ON sal.GmidId = pm_product.GmidId AND sal.TradeProductId = pm_product.TradeProductId AND
                            sal.DsmId = pm_dsm.DsmId
      WHERE pm_dsm.PmId = $pmId
        AND pm_product.UserId = $pmId
        AND (f.CampaignId IS NULL OR f.CampaignId = $campaignId)
        AND pc.ValueCenterId <> 10111) result
UNION
SELECT *,
       'Q1'    = (result.January + result.February + result.March),
       'Q2'    = (result.April + result.May + result.June),
       'Q3'    = (result.July + result.August + result.September),
       'Q4'    = (result.October + result.November + result.December),
       'Total' = result.January + result.February + result.March + result.April + result.May + result.June +
                 result.July + result.August + result.September + result.October + result.November + result.December
FROM (SELECT            country.Description AS Pais,
                        u.UserId            AS DSMId,
                        u.Fullname          AS DSM,
                        pc.Description      AS PerformanceCenter,
                        tp.TradeProductId   AS TradeProductId,
                        tp.Description      AS TradeProduct,
                        NULL                AS GmidId,
                        NULL                AS Gmid,
          'January'   = CASE
                            WHEN 1 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.January, 0)
                            ELSE isnull(f.January, 0) END,
          'February'  = CASE
                            WHEN 2 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.February, 0)
                            ELSE isnull(f.February, 0) END,
          'March'     = CASE
                            WHEN 3 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.March, 0)
                            ELSE isnull(f.March, 0) END,
          'April'     = CASE
                            WHEN 4 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.April, 0)
                            ELSE isnull(f.April, 0) END,
          'May'       = CASE
                            WHEN 5 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.May, 0)
                            ELSE isnull(f.May, 0) END,
          'June'      = CASE
                            WHEN 6 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.June, 0)
                            ELSE isnull(f.June, 0) END,
          'July'      = CASE
                            WHEN 7 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.July, 0)
                            ELSE isnull(f.July, 0) END,
          'August'    = CASE
                            WHEN 8 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.August, 0)
                            ELSE isnull(f.August, 0) END,
          'September' =CASE
                           WHEN 9 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                               THEN isnull(sal.September, 0)
                           ELSE isnull(f.September, 0) END,
          'October'   = CASE
                            WHEN 10 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.October, 0)
                            ELSE isnull(f.October, 0) END,
          'November'  = CASE
                            WHEN 11 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.November, 0)
                            ELSE isnull(f.November, 0) END,
          'December'  = CASE
                            WHEN 12 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                THEN isnull(sal.December, 0)
                            ELSE isnull(f.December, 0) END
      FROM pm_product
               INNER JOIN trade_product tp ON tp.TradeProductId = pm_product.TradeProductId
               INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
               INNER JOIN gmid g ON g.TradeProductId = tp.TradeProductId
               INNER JOIN pm_dsm ON pm_dsm.PmId = pm_product.UserId
               INNER JOIN [user] u on u.UserId = pm_dsm.DsmId
               INNER JOIN (SELECT dsm.UserId AS DsmId, cm.CountryId AS DsmCountryId
                           FROM [user] dsm
                                    INNER JOIN auth_assignment aa on dsm.UserId = aa.user_id
                                    INNER JOIN [user] sellers ON sellers.ParentId = dsm.UserId
                                    INNER JOIN client_seller cs ON cs.SellerId = sellers.UserId
                                    INNER JOIN client_marketing cm ON cm.ClientMarketingId = cs.ClientId
                           WHERE item_name = 'DSM'
                             AND dsm.IsActive = 1
                           GROUP BY dsm.UserId, dsm.Fullname, cm.CountryId) dsmcountries
                          ON dsmcountries.DsmId = pm_dsm.DsmId AND dsmcountries.DsmCountryId = g.CountryId
               INNER JOIN country ON country.CountryId = dsmcountries.DsmCountryId
               LEFT JOIN forecast_marketing f ON f.TradeProductId = pm_product.TradeProductId AND f.DsmId = pm_dsm.DsmId
               LEFT JOIN (SELECT DsmId,
                                 sales.TradeProductId,
                                 SUM(January)   AS 'January',
                                 SUM(February)  AS 'February',
                                 SUM(March)     AS 'March',
                                 SUM(Q1)        AS 'Q1',
                                 SUM(April)     AS 'April',
                                 SUM(May)       AS 'May',
                                 SUM(June)      AS 'June',
                                 SUM(Q2)        AS 'Q2',
                                 SUM(July)      AS 'July',
                                 SUM(August)    AS 'August',
                                 SUM(September) AS 'September',
                                 SUM(Q3)        AS 'Q3',
                                 SUM(October)   AS 'October',
                                 SUM(November)  AS 'November',
                                 SUM(December)  AS 'December',
                                 SUM(Q4)        AS 'Q4',
                                 SUM(Total)     AS 'Total'
                          FROM SalesByDsmAndGmidAndTradeProduct sales
                                   INNER JOIN trade_product tp ON tp.TradeProductId = sales.TradeProductId
                                   INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                          WHERE pc.ValueCenterId = 10111
                            AND sales.CampaignId = $campaignId
                          GROUP BY DsmId, sales.TradeProductId) sal
                         ON sal.TradeProductId = pm_product.TradeProductId AND sal.DsmId = pm_dsm.DsmId
      WHERE pm_dsm.PmId = $pmId
        AND pm_product.UserId = $pmId
        AND (f.CampaignId IS NULL
          OR f.CampaignId = $campaignId)
        AND pc.ValueCenterId = 10111
     ) result
ORDER BY Pais, DSM, PerformanceCenter, TradeProduct, Gmid
        ";

        $forecasts = $connection->createCommand($sql)->queryAll();

        // Read the file
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('templates/Forecast_Marketing_Offline_Template.xlsx');
        $objPHPExcel->getProperties()->setTitle($title);

        $objSheet = $objPHPExcel->getActiveSheet();

        // AUDIT
        Yii::$app->auditcomponents->createAudit(['UserId' => Yii::$app->user->identity->UserId,
            'TypeAuditId' => TypeAudit::TYPE_EXPORT_FORECAST_MARKETING_OFFLINE,
        ]);

        // WRITE VALUES IN TEMPLATE

        // WRITE HEADER
        $objSheet->setCellValue('C4', $pmId);
        $objSheet->setCellValue('C2', (int)$month);
        $objSheet->setCellValue('B2', Yii::$app->utilcomponents->getMonthES((int)$month));
        $objSheet->setCellValue('B3', $year);
        $objSheet->setCellValue('B4', $pmName);
        $objSheet->getStyle("C1:C5")->applyFromArray([
            'font' => [
                'color' => [
                    'rgb' => 'b8cce4'
                ],
            ],
        ]);

        // WRITE PRODUCTS
        $row = 6;
        foreach ($forecasts as $item) {
            $row++;
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Pais"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["DSMId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["DSM"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PerformanceCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["TradeProductId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["TradeProduct"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["GmidId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Gmid"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["January"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["February"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["March"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(I' . $row . ':K' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["April"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["May"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["June"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(M' . $row . ':O' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["July"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["August"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["September"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(Q' . $row . ':S' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["October"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["November"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["December"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(U' . $row . ':W' . $row . ')');
            $objSheet->SetCellValue(chr($charCol) . $row, '=L' . $row . '+P' . $row . '+T' . $row . '+X' . $row);

            if ($row % 2 == 0) {
                $objSheet->getStyle("A$row:Y$row")->applyFromArray([
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => 'F2F2F2'
                        ]
                    ]
                ]);
            }
        }

        $monthEnableFrom = Setting::getValue(Setting::FORECAST_ENABLE_FROM);
        $finish = ($monthEnableFrom - 1) + $this->_getAmountQuarter($monthEnableFrom - 1);
        $cellFinished = chr(73 + ($finish - 1));

        if (($monthEnableFrom - 1) > 1) {
            $objSheet->getStyle("I7:" . $cellFinished . "$row")->applyFromArray([
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'BDBDBD'
                    ],
                ],
            ]);
        }

        // SECURITY OF TEMPLATE

        // RANGE OF LOCK
        $enable = chr(69 + ($finish));
        $objSheet->getProtection()
            ->setSheet(true)
            ->setPassword('Corteva2021');
        $objSheet->getStyle($enable . '7:Y' . $row)
            ->getProtection()
            ->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

        // LOCK THE QUARTERS AND TOTAL
        $solidColor = [
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => [
                    'rgb' => 'BDBDBD'
                ]
            ]
        ];

        $columns = ['L', 'P', 'T', 'X', 'Y'];
        foreach ($columns as $column) {
            $objSheet
                ->getStyle("{$column}7:$column$row")
                ->getProtection()
                ->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);

            $objSheet->getStyle("{$column}7:$column$row")->applyFromArray($solidColor);
        }

        $this->downloadExcel($objPHPExcel, $title);
    }

    public function actionExportReportConsolid()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $campaign = Campaign::getActualCampaign();

        $sql = "
SELECT *
FROM (
         SELECT            f.CampaignId,
                           country.Description    AS Country,
                           vc.Description         AS ValueCenter,
                           pm.UserId              AS ProductManagerId,
                           pm.Fullname            AS ProductManager,
                           f.DsmId                AS DsmId,
                           u.Fullname             AS Dsm,
                           pc.PerformanceCenterId AS PerformanceCenterId,
                           pc.Description         AS PerformanceCenter,
                           f.TradeProductId       AS TradeProductId,
                           tp.Description         AS TradeProduct,
                           f.GmidId               AS GmidId,
                           g.Description          AS Gmid,
             'January'   = CASE
                               WHEN 1 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.January, 0)
                               ELSE isnull(f.January, 0) END,
             'February'  = CASE
                               WHEN 2 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.February, 0)
                               ELSE isnull(f.February, 0) END,
             'March'     = CASE
                               WHEN 3 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.March, 0)
                               ELSE isnull(f.March, 0) END,
             'April'     = CASE
                               WHEN 4 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.April, 0)
                               ELSE isnull(f.April, 0) END,
             'May'       = CASE
                               WHEN 5 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.May, 0)
                               ELSE isnull(f.May, 0) END,
             'June'      = CASE
                               WHEN 6 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.June, 0)
                               ELSE isnull(f.June, 0) END,
             'July'      = CASE
                               WHEN 7 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.July, 0)
                               ELSE isnull(f.July, 0) END,
             'August'    = CASE
                               WHEN 8 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.August, 0)
                               ELSE isnull(f.August, 0) END,
             'September' =CASE
                              WHEN 9 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                  THEN isnull(sal.September, 0)
                              ELSE isnull(f.September, 0) END,
             'October'   = CASE
                               WHEN 10 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.October, 0)
                               ELSE isnull(f.October, 0) END,
             'November'  = CASE
                               WHEN 11 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.November, 0)
                               ELSE isnull(f.November, 0) END,
             'December'  = CASE
                               WHEN 12 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.December, 0)
                               ELSE isnull(f.December, 0) END
         FROM forecast_marketing f
                  INNER JOIN pm_dsm pmdsm ON pmdsm.DsmId = f.DsmId
                  INNER JOIN gmid g on g.GmidId = f.GmidId
                  INNER JOIN trade_product tp ON tp.TradeProductId = g.TradeProductId
                  INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                  INNER JOIN value_center vc ON vc.ValueCenterId = pc.ValueCenterId
                  INNER JOIN pm_product
                             ON pm_product.GmidId = f.GmidId AND pm_product.TradeProductId = f.TradeProductId
                  INNER JOIN [user] u on u.UserId = f.DsmId
                  LEFT JOIN (SELECT DsmId,
                                    sales.TradeProductId,
                                    sales.GmidId,
                                    January,
                                    February,
                                    March,
                                    Q1,
                                    April,
                                    May,
                                    June,
                                    Q2,
                                    July,
                                    August,
                                    September,
                                    Q3,
                                    October,
                                    November,
                                    December,
                                    Q4,
                                    Total
                             FROM SalesByDsmAndGmidAndTradeProduct sales
                                      INNER JOIN trade_product tp ON tp.TradeProductId = sales.TradeProductId
                                      INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                             WHERE pc.ValueCenterId <> 10111
                                AND sales.CampaignId = $campaign->CampaignId) sal
                            ON sal.DsmId = f.DsmId AND sal.GmidId = f.GmidId
                  INNER JOIN (SELECT dsm.UserId AS DsmId, cm.CountryId AS DsmCountry
                              FROM [user] dsm
                                       INNER JOIN auth_assignment aa on dsm.UserId = aa.user_id
                                       INNER JOIN [user] sellers ON sellers.ParentId = dsm.UserId
                                       INNER JOIN client_seller cs ON cs.SellerId = sellers.UserId
                                       INNER JOIN client_marketing cm ON cm.ClientMarketingId = cs.ClientId
                              WHERE item_name = 'DSM'
                                AND dsm.IsActive = 1
                              GROUP BY dsm.UserId, dsm.Fullname, cm.CountryId) dsmcountries
                             ON dsmcountries.DsmId = f.DsmId
                  INNER JOIN country ON country.CountryId = dsmcountries.DsmCountry
                  INNER JOIN [user] pm on pm.UserId = pmdsm.PmId
         WHERE pc.ValueCenterId <> 10111
         UNION
         SELECT            f.CampaignId,
                           country.Description    AS Country,
                           vc.Description         AS ValueCenter,
                           pm.UserId              AS ProductManagerId,
                           pm.Fullname            AS ProductManager,
                           f.DsmId                AS DsmId,
                           u.Fullname             AS Dsm,
                           pc.PerformanceCenterId AS PerformanceCenterId,
                           pc.Description         AS PerformanceCenter,
                           f.TradeProductId       AS TradeProductId,
                           tp.Description         AS TradeProduct,
                           NULL                   AS GmidId,
                           NULL                   AS Gmid,
             'January'   = CASE
                               WHEN 1 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.January, 0)
                               ELSE isnull(f.January, 0) END,
             'February'  = CASE
                               WHEN 2 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.February, 0)
                               ELSE isnull(f.February, 0) END,
             'March'     = CASE
                               WHEN 3 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.March, 0)
                               ELSE isnull(f.March, 0) END,
             'April'     = CASE
                               WHEN 4 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.April, 0)
                               ELSE isnull(f.April, 0) END,
             'May'       = CASE
                               WHEN 5 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.May, 0)
                               ELSE isnull(f.May, 0) END,
             'June'      = CASE
                               WHEN 6 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.June, 0)
                               ELSE isnull(f.June, 0) END,
             'July'      = CASE
                               WHEN 7 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.July, 0)
                               ELSE isnull(f.July, 0) END,
             'August'    = CASE
                               WHEN 8 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.August, 0)
                               ELSE isnull(f.August, 0) END,
             'September' =CASE
                              WHEN 9 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                  THEN isnull(sal.September, 0)
                              ELSE isnull(f.September, 0) END,
             'October'   = CASE
                               WHEN 10 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.October, 0)
                               ELSE isnull(f.October, 0) END,
             'November'  = CASE
                               WHEN 11 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.November, 0)
                               ELSE isnull(f.November, 0) END,
             'December'  = CASE
                               WHEN 12 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.December, 0)
                               ELSE isnull(f.December, 0) END
         FROM forecast_marketing f
                  INNER JOIN pm_dsm pmdsm ON pmdsm.DsmId = f.DsmId
                  INNER JOIN trade_product tp ON tp.TradeProductId = f.TradeProductId
                  INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                  INNER JOIN value_center vc ON vc.ValueCenterId = pc.ValueCenterId
                  INNER JOIN pm_product
                             ON pm_product.TradeProductId = f.TradeProductId AND pm_product.GmidId IS NULL
                  INNER JOIN [user] u on u.UserId = f.DsmId
                  LEFT JOIN (SELECT DsmId,
                                    sales.TradeProductId,
                                    SUM(January)   AS 'January',
                                    SUM(February)  AS 'February',
                                    SUM(March)     AS 'March',
                                    SUM(Q1)        AS 'Q1',
                                    SUM(April)     AS 'April',
                                    SUM(May)       AS 'May',
                                    SUM(June)      AS 'June',
                                    SUM(Q2)        AS 'Q2',
                                    SUM(July)      AS 'July',
                                    SUM(August)    AS 'August',
                                    SUM(September) AS 'September',
                                    SUM(Q3)        AS 'Q3',
                                    SUM(October)   AS 'October',
                                    SUM(November)  AS 'November',
                                    SUM(December)  AS 'December',
                                    SUM(Q4)        AS 'Q4',
                                    SUM(Total)     AS 'Total'
                             FROM SalesByDsmAndGmidAndTradeProduct sales
                                      INNER JOIN trade_product tp ON tp.TradeProductId = sales.TradeProductId
                                      INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                             WHERE pc.ValueCenterId = 10111
                                AND sales.CampaignId = $campaign->CampaignId
                             GROUP BY DsmId, sales.TradeProductId) sal
                            ON sal.DsmId = f.DsmId AND sal.TradeProductId = f.TradeProductId
                  INNER JOIN (SELECT dsm.UserId AS DsmId, cm.CountryId AS DsmCountry
                              FROM [user] dsm
                                       INNER JOIN auth_assignment aa on dsm.UserId = aa.user_id
                                       INNER JOIN [user] sellers ON sellers.ParentId = dsm.UserId
                                       INNER JOIN client_seller cs ON cs.SellerId = sellers.UserId
                                       INNER JOIN client_marketing cm ON cm.ClientMarketingId = cs.ClientId
                              WHERE item_name = 'DSM'
                                AND dsm.IsActive = 1
                              GROUP BY dsm.UserId, dsm.Fullname, cm.CountryId) dsmcountries
                             ON dsmcountries.DsmId = f.DsmId
                  INNER JOIN country ON country.CountryId = dsmcountries.DsmCountry
                  INNER JOIN [user] pm on pm.UserId = pmdsm.PmId
         WHERE pc.ValueCenterId = 10111
     ) AS result
         UNPIVOT
         (
         Volume FOR [Month] IN (January,February,March,April,May,June,July,August,September,October,November,December)
         ) AS p
WHERE Volume <> 0 AND CampaignId = $campaign->CampaignId
        ";
//        OPTION (FORCE ORDER)

        $connection = Yii::$app->db;
        $consolidates = $connection->createCommand($sql)->queryAll();

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('templates/Reporte_Real_Ventas_Forecast_Marketing_Consolidado.xlsx');
        $objPHPExcel->setActiveSheetIndex();
        $objSheet = $objPHPExcel->getActiveSheet();

        // WRITE SHEET
        $objSheet->setCellValue('A1', Yii::t("app", "Consolid Report"));
        $objSheet->setCellValue('A2', Yii::t("app", 'All Clients'));
        $objSheet->setCellValue('A3', Yii::t("app", 'Year') . ': ' . $campaign->Name);

        // WRITE PRODUCTS
        $row = 5;
        foreach ($consolidates as $key => $item) {
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Country"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ValueCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ProductManagerId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ProductManager"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["DsmId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Dsm"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PerformanceCenterId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PerformanceCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["TradeProductId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["TradeProduct"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["GmidId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Gmid"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Month"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, Yii::$app->utilcomponents->_getNumberQuarter($item["Month"]));
            $objSheet->SetCellValue(chr($charCol) . $row, $item["Volume"]);
            if ($row % 2 == 0) {
                $objSheet->getStyle("A$row:O$row")->applyFromArray([
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => 'F2F2F2'
                        ]
                    ]
                ]);
            }

            $row++;
        }

        $title = Yii::t("app", "Report Forecast Marketing Consolid") . "(" . date("Y-m-d") . ")";
        $this->downloadExcel($objPHPExcel, $title);
    }

    private function getAllForecasts($pmId, $campaignId, $countryId)
    {
        $sqlNoSeeds = $this->getNoSeedSql($pmId, $campaignId, $countryId);

        $sqlSeeds = $this->getSeedSql($pmId, $campaignId, $countryId);

        $sql = "$sqlNoSeeds UNION $sqlSeeds ORDER BY ForecastDescription";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    private function getNoSeedProductForecasts($pmId, $campaignId, $countryId, $tradeProductId, $gmidId)
    {
        $sql = $this->getNoSeedSql($pmId, $campaignId, $countryId, "
            AND f.TradeProductId = $tradeProductId
            AND f.GmidId = $gmidId
        ");

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    private function getSeedProductForecasts($pmId, $campaignId, $countryId, $tradeProductId)
    {
        $sql = $this->getSeedSql($pmId, $campaignId, $countryId, "
            AND f.TradeProductId = $tradeProductId
        ");

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    private function getNoSeedSql($pmId, $campaignId, $countryId, $and = '')
    {
        return "
SELECT *,
       'Q1'    = (result.January + result.February + result.March),
       'Q2'    = (result.April + result.May + result.June),
       'Q3'    = (result.July + result.August + result.September),
       'Q4'    = (result.October + result.November + result.December),
       'Total' = result.January + result.February + result.March + result.April + result.May + result.June +
                 result.July + result.August + result.September + result.October + result.November + result.December
FROM (
         SELECT            f.ForecastMarketingId,
                           f.DsmId,
                           u.Fullname    AS Dsm,
                           f.CampaignId,
                           f.TradeProductId,
                           f.GmidId,
                           g.Description AS 'ForecastDescription',
             'January'   = CASE
                               WHEN 1 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.January, 0)
                               ELSE isnull(f.January, 0) END,
             'February'  = CASE
                               WHEN 2 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.February, 0)
                               ELSE isnull(f.February, 0) END,
             'March'     = CASE
                               WHEN 3 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.March, 0)
                               ELSE isnull(f.March, 0) END,
             'April'     = CASE
                               WHEN 4 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.April, 0)
                               ELSE isnull(f.April, 0) END,
             'May'       = CASE
                               WHEN 5 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.May, 0)
                               ELSE isnull(f.May, 0) END,
             'June'      = CASE
                               WHEN 6 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.June, 0)
                               ELSE isnull(f.June, 0) END,
             'July'      = CASE
                               WHEN 7 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.July, 0)
                               ELSE isnull(f.July, 0) END,
             'August'    = CASE
                               WHEN 8 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.August, 0)
                               ELSE isnull(f.August, 0) END,
             'September' =CASE
                              WHEN 9 <
                                   (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                  THEN isnull(sal.September, 0)
                              ELSE isnull(f.September, 0) END,
             'October'   = CASE
                               WHEN 10 < (SELECT TOP 1 Value
                                          FROM setting
                                          where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.October, 0)
                               ELSE isnull(f.October, 0) END,
             'November'  = CASE
                               WHEN 11 < (SELECT TOP 1 Value
                                          FROM setting
                                          where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.November, 0)
                               ELSE isnull(f.November, 0) END,
             'December'  = CASE
                               WHEN 12 < (SELECT TOP 1 Value
                                          FROM setting
                                          where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.December, 0)
                               ELSE isnull(f.December, 0) END
         FROM forecast_marketing f
                  INNER JOIN pm_dsm pmdsm ON pmdsm.DsmId = f.DsmId
                  INNER JOIN gmid g on g.GmidId = f.GmidId
                  INNER JOIN trade_product tp ON tp.TradeProductId = g.TradeProductId
                  INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                  INNER JOIN pm_product
                             ON pm_product.GmidId = f.GmidId AND pm_product.TradeProductId = f.TradeProductId
                  INNER JOIN [user] u on u.UserId = f.DsmId
                  LEFT JOIN (SELECT DsmId,
                                    sales.TradeProductId,
                                    sales.GmidId,
                                    January,
                                    February,
                                    March,
                                    Q1,
                                    April,
                                    May,
                                    June,
                                    Q2,
                                    July,
                                    August,
                                    September,
                                    Q3,
                                    October,
                                    November,
                                    December,
                                    Q4,
                                    Total
                             FROM SalesByDsmAndGmidAndTradeProduct sales
                                      INNER JOIN trade_product tp ON tp.TradeProductId = sales.TradeProductId
                                      INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                             WHERE pc.ValueCenterId <> 10111
                                AND sales.CampaignId = $campaignId) sal
                            ON sal.DsmId = f.DsmId AND sal.GmidId = f.GmidId
                  INNER JOIN (SELECT dsm.UserId   AS DsmId,
                            cm.CountryId AS DsmCountry
                     FROM [user] dsm
                              INNER JOIN auth_assignment aa on dsm.UserId = aa.user_id
                              INNER JOIN [user] sellers ON sellers.ParentId = dsm.UserId
                              INNER JOIN client_seller cs ON cs.SellerId = sellers.UserId
                              INNER JOIN client_marketing cm ON cm.ClientMarketingId = cs.ClientId
                     WHERE item_name = 'DSM'
                       AND dsm.IsActive = 1
                     GROUP BY dsm.UserId, dsm.Fullname, cm.CountryId) dsmcountries ON dsmcountries.DsmId = f.DsmId
         WHERE pmdsm.PmId = $pmId
           AND pm_product.UserId = $pmId
           AND f.CampaignId = $campaignId
           AND g.CountryId = $countryId
           AND DsmCountry = $countryId
           AND pc.ValueCenterId <> 10111
           $and
     ) result
        ";
    }

    private function getSeedSql($pmId, $campaignId, $countryId, $and = '')
    {
        return "
        SELECT *,
        'Q1'    = (result.January + result.February + result.March),
        'Q2'    = (result.April + result.May + result.June),
        'Q3'    = (result.July + result.August + result.September),
        'Q4'    = (result.October + result.November + result.December),
        'Total' = result.January + result.February + result.March + result.April + result.May + result.June +
                  result.July + result.August + result.September + result.October + result.November + result.December
 FROM (SELECT res.ForecastMarketingId,
              res.DsmId,
              res.Dsm,
              res.CampaignId,
              res.TradeProductId,
              res.GmidId,
              res.ForecastDescription,
              'January'   = CASE
                                WHEN 1 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(SalJanuary, 0)
                                ELSE isnull(January, 0) END,
              'February'  = CASE
                                WHEN 2 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salFebruary, 0)
                                ELSE isnull(February, 0) END,
              'March'     = CASE
                                WHEN 3 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salMarch, 0)
                                ELSE isnull(March, 0) END,
              'April'     = CASE
                                WHEN 4 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salApril, 0)
                                ELSE isnull(April, 0) END,
              'May'       = CASE
                                WHEN 5 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salMay, 0)
                                ELSE isnull(May, 0) END,
              'June'      = CASE
                                WHEN 6 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salJune, 0)
                                ELSE isnull(June, 0) END,
              'July'      = CASE
                                WHEN 7 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salJuly, 0)
                                ELSE isnull(July, 0) END,
              'August'    = CASE
                                WHEN 8 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salAugust, 0)
                                ELSE isnull(August, 0) END,
              'September' =CASE
                               WHEN 9 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(salSeptember, 0)
                               ELSE isnull(September, 0) END,
              'October'   = CASE
                                WHEN 10 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salOctober, 0)
                                ELSE isnull(October, 0) END,
              'November'  = CASE
                                WHEN 11 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salNovember, 0)
                                ELSE isnull(November, 0) END,
              'December'  = CASE
                                WHEN 12 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                    THEN isnull(salDecember, 0)
                                ELSE isnull(December, 0) END
       FROM (
                SELECT f.ForecastMarketingId,
                       f.DsmId,
                       u.Fullname     AS Dsm,
                       f.CampaignId,
                       f.TradeProductId,
                       f.GmidId,
                       tp.Description AS 'ForecastDescription',
                       sal.January    AS SalJanuary,
                       sal.February   AS SalFebruary,
                       sal.March      AS SalMarch,
                       sal.April      AS SalApril,
                       sal.May        AS SalMay,
                       sal.June       AS SalJune,
                       sal.July       AS SalJuly,
                       sal.August     AS SalAugust,
                       sal.September  AS SalSeptember,
                       sal.October    AS SalOctober,
                       sal.November   AS SalNovember,
                       sal.December   AS SalDecember,
                       f.January,
                       f.February,
                       f.March,
                       f.April,
                       f.May,
                       f.June,
                       f.July,
                       f.August,
                       f.September,
                       f.October,
                       f.November,
                       f.December
                FROM forecast_marketing f
                         INNER JOIN pm_dsm pmdsm ON pmdsm.DsmId = f.DsmId
                         INNER JOIN trade_product tp ON tp.TradeProductId = f.TradeProductId
                         INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                         INNER JOIN pm_product
                                    ON pm_product.TradeProductId = f.TradeProductId
									--AND pm_product.GmidId IS NULL
                         INNER JOIN [user] u on u.UserId = f.DsmId
                         LEFT JOIN (SELECT DsmId,
                                           sales.TradeProductId,
                                           SUM(January)   AS 'January',
                                           SUM(February)  AS 'February',
                                           SUM(March)     AS 'March',
                                           SUM(Q1)        AS 'Q1',
                                           SUM(April)     AS 'April',
                                           SUM(May)       AS 'May',
                                           SUM(June)      AS 'June',
                                           SUM(Q2)        AS 'Q2',
                                           SUM(July)      AS 'July',
                                           SUM(August)    AS 'August',
                                           SUM(September) AS 'September',
                                           SUM(Q3)        AS 'Q3',
                                           SUM(October)   AS 'October',
                                           SUM(November)  AS 'November',
                                           SUM(December)  AS 'December',
                                           SUM(Q4)        AS 'Q4',
                                           SUM(Total)     AS 'Total'
                                    FROM SalesByDsmAndGmidAndTradeProduct sales
                                             INNER JOIN trade_product tp ON tp.TradeProductId = sales.TradeProductId
                                             INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                                    WHERE pc.ValueCenterId = 10111
                                      AND sales.CampaignId = $campaignId
                                    GROUP BY DsmId, sales.TradeProductId) sal
                                   ON sal.DsmId = f.DsmId AND sal.TradeProductId = f.TradeProductId
                         INNER JOIN (SELECT dsm.UserId AS DsmId, cm.CountryId AS DsmCountry
                                     FROM [user] dsm
                                              INNER JOIN auth_assignment aa on dsm.UserId = aa.user_id
                                              INNER JOIN [user] sellers ON sellers.ParentId = dsm.UserId
                                              INNER JOIN client_seller cs ON cs.SellerId = sellers.UserId
                                              INNER JOIN client_marketing cm ON cm.ClientMarketingId = cs.ClientId
                                     WHERE item_name = 'DSM'
                                       AND dsm.IsActive = 1
                                     GROUP BY dsm.UserId, dsm.Fullname, cm.CountryId) dsmcountries
                                    ON dsmcountries.DsmId = f.DsmId
                WHERE pmdsm.PmId = $pmId
                  AND pm_product.UserId = $pmId
                  AND f.CampaignId = $campaignId
                  AND (SELECT TOP 1 CountryId
                       FROM gmid
                       WHERE TradeProductId = f.TradeProductId
                       GROUP BY CountryId, TradeProductId) = 1
                  AND DsmCountry = $countryId
                  AND pc.ValueCenterId = 10111
                  $and
                GROUP BY f.ForecastMarketingId, f.DsmId, u.Fullname, f.CampaignId, f.TradeProductId, f.GmidId,
                         tp.Description,
                         sal.January, sal.February, sal.March, sal.April, sal.May, sal.June, sal.July, sal.August,
                         sal.September,
                         sal.October, sal.November, sal.December, f.January, f.February, f.March, f.April, f.May,
                         f.June, f.July,
                         f.August, f.September, f.October, f.November, f.December
            ) res) result
        ";
        return "
SELECT *,
       'Q1'    = (result.January + result.February + result.March),
       'Q2'    = (result.April + result.May + result.June),
       'Q3'    = (result.July + result.August + result.September),
       'Q4'    = (result.October + result.November + result.December),
       'Total' = result.January + result.February + result.March + result.April + result.May + result.June +
                 result.July + result.August + result.September + result.October + result.November + result.December
FROM (
         SELECT            f.ForecastMarketingId,
                           f.DsmId,
                           u.Fullname     AS Dsm,
                           f.CampaignId,
                           f.TradeProductId,
                           f.GmidId,
                           tp.Description AS 'ForecastDescription',
             'January'   = CASE
                               WHEN 1 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.January, 0)
                               ELSE isnull(f.January, 0) END,
             'February'  = CASE
                               WHEN 2 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.February, 0)
                               ELSE isnull(f.February, 0) END,
             'March'     = CASE
                               WHEN 3 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.March, 0)
                               ELSE isnull(f.March, 0) END,
             'April'     = CASE
                               WHEN 4 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.April, 0)
                               ELSE isnull(f.April, 0) END,
             'May'       = CASE
                               WHEN 5 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.May, 0)
                               ELSE isnull(f.May, 0) END,
             'June'      = CASE
                               WHEN 6 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.June, 0)
                               ELSE isnull(f.June, 0) END,
             'July'      = CASE
                               WHEN 7 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.July, 0)
                               ELSE isnull(f.July, 0) END,
             'August'    = CASE
                               WHEN 8 < (SELECT TOP 1 Value
                                         FROM setting
                                         where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.August, 0)
                               ELSE isnull(f.August, 0) END,
             'September' =CASE
                              WHEN 9 <
                                   (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                  THEN isnull(sal.September, 0)
                              ELSE isnull(f.September, 0) END,
             'October'   = CASE
                               WHEN 10 < (SELECT TOP 1 Value
                                          FROM setting
                                          where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.October, 0)
                               ELSE isnull(f.October, 0) END,
             'November'  = CASE
                               WHEN 11 < (SELECT TOP 1 Value
                                          FROM setting
                                          where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.November, 0)
                               ELSE isnull(f.November, 0) END,
             'December'  = CASE
                               WHEN 12 < (SELECT TOP 1 Value
                                          FROM setting
                                          where DisplayName = 'FORECAST_ENABLE_FROM')
                                   THEN isnull(sal.December, 0)
                               ELSE isnull(f.December, 0) END
         FROM forecast_marketing f
                  INNER JOIN pm_dsm pmdsm ON pmdsm.DsmId = f.DsmId
                  INNER JOIN trade_product tp ON tp.TradeProductId = f.TradeProductId
                  INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
                  INNER JOIN pm_product
                             ON pm_product.TradeProductId = f.TradeProductId AND pm_product.GmidId IS NULL
                  INNER JOIN [user] u on u.UserId = f.DsmId
                  LEFT JOIN
              (SELECT DsmId,
                      sales.TradeProductId,
                      SUM(January)   AS 'January',
                      SUM(February)  AS 'February',
                      SUM(March)     AS 'March',
                      SUM(Q1)        AS 'Q1',
                      SUM(April)     AS 'April',
                      SUM(May)       AS 'May',
                      SUM(June)      AS 'June',
                      SUM(Q2)        AS 'Q2',
                      SUM(July)      AS 'July',
                      SUM(August)    AS 'August',
                      SUM(September) AS 'September',
                      SUM(Q3)        AS 'Q3',
                      SUM(October)   AS 'October',
                      SUM(November)  AS 'November',
                      SUM(December)  AS 'December',
                      SUM(Q4)        AS 'Q4',
                      SUM(Total)     AS 'Total'
               FROM SalesByDsmAndGmidAndTradeProduct sales
                        INNER JOIN trade_product tp ON tp.TradeProductId = sales.TradeProductId
                        INNER JOIN performance_center pc ON pc.PerformanceCenterId = tp.PerformanceCenterId
               WHERE pc.ValueCenterId = 10111
                AND sales.CampaignId = $campaignId
               GROUP BY DsmId, sales.TradeProductId) sal
              ON sal.DsmId = f.DsmId AND sal.TradeProductId = f.TradeProductId
         INNER JOIN (SELECT dsm.UserId   AS DsmId,
                            cm.CountryId AS DsmCountry
                     FROM [user] dsm
                              INNER JOIN auth_assignment aa on dsm.UserId = aa.user_id
                              INNER JOIN [user] sellers ON sellers.ParentId = dsm.UserId
                              INNER JOIN client_seller cs ON cs.SellerId = sellers.UserId
                              INNER JOIN client_marketing cm ON cm.ClientMarketingId = cs.ClientId
                     WHERE item_name = 'DSM'
                       AND dsm.IsActive = 1
                     GROUP BY dsm.UserId, dsm.Fullname, cm.CountryId) dsmcountries ON dsmcountries.DsmId = f.DsmId
         WHERE pmdsm.PmId = $pmId
           AND pm_product.UserId = $pmId
           AND f.CampaignId = $campaignId
           AND (SELECT TOP 1
                             CountryId FROM gmid WHERE TradeProductId = f.TradeProductId GROUP BY CountryId, TradeProductId) = $countryId
           AND DsmCountry = $countryId
           AND pc.ValueCenterId = 10111
           $and
     ) result
        ";
    }

    private function getFilteredForecastProductsByPm($pmId, $countryId)
    {
        // No Seeds
        $sqlNoSeeds = "
SELECT pm_product.TradeProductId,
       pm_product.GmidId,
       g.Description
FROM pm_product
         INNER JOIN trade_product tp ON tp.TradeProductId = pm_product.TradeProductId
         INNER JOIN performance_center pc ON tp.PerformanceCenterId = pc.PerformanceCenterId
         INNER JOIN gmid g ON g.GmidId = pm_product.GmidId
WHERE pm_product.UserId = $pmId
  --AND pm_product.GmidId IS NOT NULL
  AND pc.ValueCenterId <> 10111
  AND g.CountryId = $countryId
        ";

        // Seeds
        $sqlSeeds = "
SELECT pm_product.TradeProductId,
       NULL AS 'GmidId',
       tp.Description
FROM pm_product
         INNER JOIN trade_product tp ON tp.TradeProductId = pm_product.TradeProductId
         INNER JOIN performance_center pc ON tp.PerformanceCenterId = pc.PerformanceCenterId
         INNER JOIN gmid g ON g.TradeProductId = tp.TradeProductId
WHERE pm_product.UserId = $pmId
  --AND pm_product.GmidId IS NULL
  AND pc.ValueCenterId = 10111
  AND g.CountryId = $countryId
GROUP BY pm_product.TradeProductId, tp.Description
        ";

        $sql = "$sqlNoSeeds UNION $sqlSeeds ORDER BY Description";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    private function _getAmountQuarter($month)
    {
        $quarter = 0;
        if ($month >= 4 && $month <= 6)
            $quarter = 1;
        elseif ($month >= 7 && $month <= 9)
            $quarter = 2;
        elseif ($month >= 10 && $month <= 12)
            $quarter = 3;
        return $quarter;
    }

    private function downloadExcel(PHPExcel $objPHPExcel, string $title)
    {
        // EXPORT EXCEL TO IMPORT
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$title.xlsx\"");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
