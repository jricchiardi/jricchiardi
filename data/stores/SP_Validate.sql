-- ADD SERVER TEMPORALY FOR READ ALL ROWS
  EXEC sp_addlinkedserver
    @server = 'ExcelServerCustomer',
    @srvproduct = 'Excel', 
    @provider = 'Microsoft.ACE.OLEDB.12.0',
    @datasrc = 'C:\xampp\htdocs\dow.forecast\data\excels\Customer.xlsx',
    @provstr = 'Excel 12.0;IMEX=1;HDR=YES;';
GO
-- INSERT ROWS FROM EXCEL TO TABLE TEMPORALLY
    SELECT * INTO #TEMP_CUSTOMER FROM OPENQUERY(ExcelServerCustomer, 'SELECT * FROM [SAPBW_DOWNLOAD$]');
GO 