
/****************** ADD SERVER TEMPORALY FOR READ ALL ROWS ******************************************************/


ALTER PROCEDURE SP_ImportCustomer
AS
  SET NOCOUNT ON;


/********************************************	VALIDATIONS ************************************************************/

	CREATE TABLE #ERRORS
	(
		CLIENT INT,
		DESCRIPTION VARCHAR(150),
		CAUSE VARCHAR(50)
	)
	
	-- VALIDATE DUPLICATE CLIENT

	INSERT INTO #ERRORS(CLIENT,DESCRIPTION,CAUSE)
	SELECT [Liable Customer],F3,'CLIENTE DUPLICADO'
	FROM TEMP_CUSTOMER
	GROUP BY [Liable Customer],F3
	HAVING COUNT(*) > 1;

	IF (SELECT COUNT(1) FROM #ERRORS)> 0 BEGIN 
		SELECT * FROM #ERRORS;
	END	 
	ELSE BEGIN

	SELECT * FROM #ERRORS;


/******************************************** COUNTRY ***************************************************/

-- UPDATE COUNTRY
UPDATE country SET	Description = temp.Country 					
FROM country c
INNER JOIN TEMP_CUSTOMER temp
ON c.Description = temp.Country


-- INSERT COUNTRY
INSERT country(Description)
SELECT DISTINCT temp.Country
FROM TEMP_CUSTOMER temp
LEFT JOIN country c
ON c.Description = temp.Country
WHERE c.CountryId IS NULL

/******************************************	CLIENT_TYPE	******************************************************/

-- UPDATE CLIENTS TYPES EXISTING
 UPDATE client_type SET Description = temp.Clasificacion
 FROM client_type ct 
 INNER JOIN TEMP_CUSTOMER temp 
 ON ct.Description = temp.Clasificacion



-- INSERTS NEWS CLIENTS TYPE
INSERT INTO client_type (Description)
SELECT DISTINCT tc.Clasificacion
FROM TEMP_CUSTOMER tc 
LEFT JOIN client_type ct 
ON ct.Description = tc.Clasificacion
WHERE ct.ClientTypeId IS NULL


/******************************************		USERS		*******************************************************/

-- INSERT RSMs 
INSERT INTO [user](DowUserId,Fullname,Email,Username,PasswordHash)
SELECT DISTINCT temp.RSM,
	   temp.F12,
	   temp.[Mail RSM],	   
	   CONCAT(replace(temp.[Mail RSM],'@dow.com',''),'rsm'),
	   '1c63129ae9db9c60c3e8aa94d3e00495'
FROM TEMP_CUSTOMER temp
LEFT JOIN [user] u 
ON u.DowUserId = temp.RSM
WHERE u.UserId IS NULL
 

-- INSERT DSMs 
INSERT INTO [user](DowUserId,ParentId,Fullname,Email,Username,PasswordHash)
SELECT DISTINCT 
	   temp.DSM,
	   rsm.UserId,
	   temp.F9,
	   temp.[Mail DSM],	   
	   CONCAT(replace(temp.[Mail DSM],'@dow.com',''),'dsm') AS Username,
	   '1c63129ae9db9c60c3e8aa94d3e00495' AS PasswordHash
FROM TEMP_CUSTOMER temp
LEFT JOIN 
( SELECT u.UserId,u.DowUserId
  FROM [user] u 
  WHERE CHARINDEX('dsm',u.Username) > 0 
) u
ON u.DowUserId = temp.DSM
INNER JOIN [user] rsm
ON rsm.DowUserId = temp.RSM
WHERE u.UserId IS NULL  AND CHARINDEX('rsm',rsm.Username) > 0

 
-- INSERT Sellers 
INSERT INTO [user](DowUserId,ParentId,Fullname,Email,Username,PasswordHash)
SELECT DISTINCT 
	   temp.[Field Seller],
	   dsm.UserId,
	   temp.F6,
	   temp.[Mail vendedor],	   
	   replace(temp.[Mail vendedor],'@dow.com','') AS Username,
	   '1c63129ae9db9c60c3e8aa94d3e00495' AS PasswordHash
FROM TEMP_CUSTOMER temp
LEFT JOIN 
( SELECT u.UserId,u.DowUserId
  FROM [user] u 
  WHERE NOT( CHARINDEX('dsm',u.Username) > 0 OR CHARINDEX('rsm',u.Username) > 0 OR u.Username ='admin')
) u
ON u.DowUserId = temp.[Field Seller]
INNER JOIN [user] dsm
ON dsm.DowUserId = temp.DSM
WHERE u.UserId IS NULL AND CHARINDEX('dsm',dsm.Username) > 0
ORDER BY UserId





-- INSERT ROLES
DELETE FROM auth_assignment;


INSERT INTO auth_assignment(user_id,item_name)
SELECT u.UserId, CASE WHEN u.Username like '%rsm%'  THEN 'RSM'
					  WHEN u.Username like '%dsm%'  THEN 'DSM'	
					  WHEN u.Username like 'admin'  THEN 'admin'	
			     ELSE 
					'SELLER'
			   END AS item_name
FROM [user] u



-- INSERT CLIENTS OTHERS


INSERT INTO client(ClientId,Description,IsGroup,CountryId,IsActive)
SELECT -u.UserId ,'OTROS',1,(SELECT TOP 1 CountryId 
							 FROM client cli
							 INNER JOIN client_seller cs 
							 ON cs.ClientId = cli.ClientId 
							 INNER JOIN [user] s 
							 ON s.UserId = cs.SellerId
							 WHERE u.UserId = s.UserId AND cli.CountryId IS NOT NULL
							 ) AS CountryId
							 ,1
FROM [user] u 
INNER JOIN auth_assignment asg
ON u.UserId = asg.user_id
WHERE asg.item_name = 'SELLER' AND NOT EXISTS(SELECT * FROM client ex WHERE ex.ClientId = -u.UserId )

-- UPDATE RSMs
UPDATE [user] SET Fullname = temp.F12, 
				  Email =  temp.[Mail RSM] , 
				  Username = CONCAT(replace(temp.[Mail RSM],'@dow.com',''),'rsm')
FROM [user] u 
INNER JOIN TEMP_CUSTOMER temp
ON u.DowUserId = temp.RSM
INNER JOIN auth_assignment asg
ON asg.user_id = u.UserId
WHERE asg.item_name = 'RSM'


-- UPDATE DSMs
UPDATE [user] SET Fullname = temp.F9, 
				  Email =  temp.[Mail DSM] , 
				  Username = CONCAT(replace(temp.[Mail DSM],'@dow.com',''),'dsm')
FROM [user] u 
INNER JOIN TEMP_CUSTOMER temp
ON u.DowUserId = temp.DSM
INNER JOIN auth_assignment asg
ON asg.user_id = u.UserId
WHERE asg.item_name = 'DSM'


-- UPDATE SELLERs
UPDATE [user] SET Fullname = temp.F6, 
				  Email = temp.[Mail vendedor] , 
				  Username = replace(temp.[Mail vendedor],'@dow.com','')
FROM [user] u 
INNER JOIN TEMP_CUSTOMER temp
ON u.DowUserId = temp.[Field Seller]
INNER JOIN auth_assignment asg
ON asg.user_id = u.UserId
WHERE asg.item_name = 'SELLER'


/******************************************		CLIENTS		*******************************************************/


-- INSERT NEWS CLIENTS

INSERT INTO client(ClientId, ClientTypeId, IsGroup, CountryId, Description , IsActive)
SELECT temp.[Liable Customer],ct.ClientTypeId, 0 ,cou.CountryId,temp.F3,1
FROM TEMP_CUSTOMER temp
LEFT JOIN client_type ct 
ON ct.Description = temp.Clasificacion
LEFT JOIN country cou 
ON cou.Description = temp.Country
LEFT JOIN client c
ON c.ClientId = temp.[Liable Customer]
WHERE c.ClientId IS NULL



--INSERT INTO client_seller
DELETE FROM client_seller;


-- INSERT RELATIONS CLIENT SELLER
INSERT INTO client_seller(ClientId,SellerId)
SELECT temp.[Liable Customer],
 	   s.UserId
FROM  TEMP_CUSTOMER temp
INNER JOIN [client] c
ON c.ClientId = temp.[Liable Customer]
INNER JOIN [user] s
ON s.DowUserId = temp.[Field Seller]
INNER JOIN auth_assignment asg
ON asg.user_id = s.UserId
WHERE asg.item_name = 'SELLER'

-- INSERT OTHERS in client_seller

INSERT INTO client_seller(ClientId,SellerId)
SELECT -u.UserId,u.UserId 
FROM [user] u 
INNER JOIN auth_assignment asg
ON u.UserId = asg.user_id
WHERE asg.item_name = 'SELLER'

-- UPDATE CLIENTS
UPDATE client SET Description = temp.F3, 
				  ClientTypeId = ct.ClientTypeId,
				  CountryId = cou.CountryId ,
				  IsGroup = 0, 
				  GroupId = CASE WHEN ct.Description = 'OTROS' THEN -cs.SellerId  ELSE NULL END
FROM client c 
INNER JOIN TEMP_CUSTOMER temp 
ON c.ClientId = temp.[Liable Customer]
INNER JOIN client_type ct 
ON ct.Description = temp.Clasificacion
INNER JOIN country cou 
ON cou.Description = temp.Country
INNER JOIN client_seller cs 
ON cs.ClientId = c.ClientId

-- UPDATE COUNTRIES CLIENT OTHERS IS VERY DIFICULT
UPDATE client  SET CountryId = 
					 ( SELECT TOP 1 c.CountryId
					  FROM client c 
					  INNER JOIN client_seller csi 
					  ON c.ClientId = csi.ClientId 
					  WHERE csi.SellerId = cs.SellerId AND csi.ClientId >0					  
					  ) 
FROM client cli 
INNER JOIN client_seller cs 
ON cli.ClientId = cs.ClientId
WHERE cli.ClientId < 0

  END;
    DELETE FROM TEMP_CUSTOMER;

GO



EXEC SP_ImportCustomer;






