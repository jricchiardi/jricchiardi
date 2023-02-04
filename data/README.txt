1 - Create dow.forecast database on SQL SErver Instance
2 - Execute yii migrate
3 - Extract 20150728_data.zip
4 - Execute: sqlcmd -S .\SQLEXPRESS -d dow.forecast -i script.sql -a 32767

5- 
-- INSTALL Microsoft.ACE.OLEDB.12.0 AND TEST WITH FILE C:\import\usuarios.xlsx

EXEC sp_MSset_oledb_prop;
GO
EXEC sp_MSset_oledb_prop N'Microsoft.ACE.OLEDB.12.0', N'AllowInProcess', 1
GO
EXEC sp_MSset_oledb_prop N'Microsoft.ACE.OLEDB.12.0', N'DynamicParameters', 1
GO
EXEC sp_addlinkedserver
    @server = 'ExcelServer2',
    @srvproduct = 'Excel', 
    @provider = 'Microsoft.ACE.OLEDB.12.0',
    @datasrc = 'C:\import\usuarios.xlsx',
    @provstr = 'Excel 12.0;IMEX=1;HDR=YES;'

SELECT * FROM OPENQUERY(ExcelServer2, 'SELECT * FROM [a$]')

	EXEC sp_dropserver
    @server = 'ExcelServer2',
    @droplogins='droplogins'


-- ADD LOGIN FOR SERVER_PRODUCT
EXEC sp_addlinkedsrvlogin ExcelServerProduct, FALSE, sa, NULL


/****************** ADD SERVER TEMPORALY FOR READ ALL ROWS ******************************************************/
/*
  EXEC sp_addlinkedserver
    @server = 'ExcelServerCustomer',
    @srvproduct = 'Excel', 
    @provider = 'Microsoft.ACE.OLEDB.12.0',
    @datasrc = 'C:\xampp\htdocs\dow.forecast\frontend\web\uploads\Customer.xlsx',
    @provstr = 'Excel 12.0;IMEX=1;HDR=YES;';
GO
*/
/***************************************** CLOSE SERVERS AND CONECTIONS *******************************************/

-- DELETE ALL CONECTIONS	
/*
GO
  	EXEC sp_dropserver
     @server = 'ExcelServerCustomer',
     @droplogins='droplogins';
*/