<?php

use yii\db\Schema;
use yii\db\Migration;

class m150727_155909_Database extends Migration
{
    public function up()
    {
$sql = "
CREATE TABLE [dbo].[auth_assignment](
	[item_name] [nvarchar](64) NOT NULL,
	[user_id] [int] NOT NULL,
	[created_at] [int] NULL DEFAULT (NULL),
 CONSTRAINT [PK_auth_assignment_item_name] PRIMARY KEY CLUSTERED 
(
	[item_name] ASC,
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[auth_item]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[auth_item](
	[name] [nvarchar](64) NOT NULL,
	[type] [int] NOT NULL,
	[description] [nvarchar](max) NULL,
	[rule_name] [nvarchar](64) NULL DEFAULT (NULL),
	[data] [nvarchar](max) NULL,
	[created_at] [int] NULL DEFAULT (NULL),
	[updated_at] [int] NULL DEFAULT (NULL),
 CONSTRAINT [PK_auth_item_name] PRIMARY KEY CLUSTERED 
(
	[name] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


/****** Object:  Table [dbo].[auth_item_child]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[auth_item_child](
	[parent] [nvarchar](64) NOT NULL,
	[child] [nvarchar](64) NOT NULL,
 CONSTRAINT [PK_auth_item_child_parent] PRIMARY KEY CLUSTERED 
(
	[parent] ASC,
	[child] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[auth_rule]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[auth_rule](
	[name] [nvarchar](64) NOT NULL,
	[data] [nvarchar](max) NULL,
	[created_at] [int] NULL,
	[updated_at] [int] NULL,
 CONSTRAINT [PK_auth_rule_name] PRIMARY KEY CLUSTERED 
(
	[name] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


/****** Object:  Table [dbo].[campaign]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[campaign](
	[CampaignId] [int] IDENTITY(3,1) NOT NULL,
	[Name] [nvarchar](50) NOT NULL,
	[IsFuture] [bit] NOT NULL CONSTRAINT [DF__user__IsFuture__5535A961]  DEFAULT ((1)),
	[IsActual] [bit] NOT NULL CONSTRAINT [DF__user__IsActual__5535A963]  DEFAULT ((1)),
	[PlanDateTo] [datetime] NULL,
	[PlanDateFrom] [datetime] NULL,
	[PlanSettingDateTo] [datetime2](0) NULL,
	[PlanSettingDateFrom] [datetime] NULL,
	[IsActive] [bit] NOT NULL CONSTRAINT [DF__user__IsActive__5535A964]  DEFAULT ((1)),
 CONSTRAINT [PK_campaign_CampaignId] PRIMARY KEY CLUSTERED 
(
	[CampaignId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[city]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[city](
	[CityId] [int] IDENTITY(22974,1) NOT NULL,
	[Name] [nvarchar](255) NOT NULL,
	[StateId] [int] NOT NULL,
	[IsActive] [binary](1) NOT NULL,
 CONSTRAINT [PK_city_CityId] PRIMARY KEY CLUSTERED 
(
	[CityId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [city\$UkNameState] UNIQUE NONCLUSTERED 
(
	[Name] ASC,
	[StateId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[client]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[client](
	[ClientId] [int] NOT NULL,
	[ClientTypeId] [int] NULL,
	[GroupId] [int] NULL,
	[CountryId] [int] NULL,
	[Description] [varchar](150) NOT NULL,
	[IsGroup] [bit] NULL,
	[IsActive] [bit] NOT NULL CONSTRAINT [DF_client_IsActive]  DEFAULT ((1)),
 CONSTRAINT [PK_client] PRIMARY KEY CLUSTERED 
(
	[ClientId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[client_product]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[client_product](
	[ClientProductId] [int] IDENTITY(1,1) NOT NULL,
	[GmidId] [varchar](20) NULL,
	[TradeProductId] [varchar](20) NULL,
	[ClientId] [int] NULL,
	[IsForecastable] [bit] NULL,
 CONSTRAINT [PK_client_product] PRIMARY KEY CLUSTERED 
(
	[ClientProductId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[client_seller]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[client_seller](
	[ClientId] [int] NOT NULL,
	[SellerId] [int] NOT NULL,
 CONSTRAINT [PK_client_seller] PRIMARY KEY CLUSTERED 
(
	[ClientId] ASC,
	[SellerId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[client_type]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[client_type](
	[ClientTypeId] [int] IDENTITY(1,1) NOT NULL,
	[Description] [varchar](50) NULL,
	[IsActive] [bit] NOT NULL CONSTRAINT [DF_client_type_IsActive]  DEFAULT ((1)),
 CONSTRAINT [PK_client_type] PRIMARY KEY CLUSTERED 
(
	[ClientTypeId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[country]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[country](
	[CountryId] [int] IDENTITY(1,1) NOT NULL,
	[Abbreviation] [varchar](5) NULL,
	[Description] [nvarchar](255) NOT NULL,
	[IsActive] [bit] NOT NULL CONSTRAINT [DF__country__IsActiv__35BCFE0A]  DEFAULT ((1)),
 CONSTRAINT [PK_country] PRIMARY KEY CLUSTERED 
(
	[CountryId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [country\$UkName] UNIQUE NONCLUSTERED 
(
	[Description] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[forecast]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[forecast](
	[ClientProductId] [int] NOT NULL,
	[CampaignId] [int] NOT NULL,
	[January] [int] NULL,
	[February] [int] NULL,
	[March] [int] NULL,
	[Q1] [int] NULL,
	[April] [int] NULL,
	[May] [int] NULL,
	[June] [int] NULL,
	[Q2] [int] NULL,
	[July] [int] NULL,
	[August] [int] NULL,
	[September] [int] NULL,
	[Q3] [int] NULL,
	[October] [int] NULL,
	[November] [int] NULL,
	[December] [int] NULL,
	[Q4] [int] NULL,
	[Total] [int] NULL,
 CONSTRAINT [PK_forecast_1] PRIMARY KEY CLUSTERED 
(
	[ClientProductId] ASC,
	[CampaignId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[gmid]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[gmid](
	[GmidId] [varchar](20) NOT NULL,
	[Description] [varchar](150) NULL,
	[TradeProductId] [varchar](20) NULL,
	[Price] [decimal](10, 2) NULL,
	[Profit] [decimal](5, 2) NULL,
	[CountryId] [int] NULL,
	[IsForecastable] [bit] NULL CONSTRAINT [DF_gmid_IsForecastable]  DEFAULT ((1)),
	[IsActive] [bit] NULL CONSTRAINT [DF_gmid_IsActive_1]  DEFAULT ((1)),
 CONSTRAINT [PK_gmid_1] PRIMARY KEY CLUSTERED 
(
	[GmidId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[import]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[import](
	[ImportId] [int] IDENTITY(1,1) NOT NULL,
	[Name] [varchar](250) NOT NULL,
	[CreatedAt] [datetime] NULL,
	[TypeImportId] [int] NOT NULL,
 CONSTRAINT [PK_import] PRIMARY KEY CLUSTERED 
(
	[ImportId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]



SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[notification]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[notification](
	[NotificationId] [int] IDENTITY(230,1) NOT NULL,
	[FromUserId] [int] NULL,
	[Description] [nvarchar](255) NULL,
	[ToUserId] [int] NULL,
	[ToProfileId] [nvarchar](64) NULL,
	[ObjectId] [int] NULL,
	[CreatedAt] [datetime2](0) NULL,
	[NotificationStatusId] [int] NULL,
 CONSTRAINT [PK_notification_NotificationId] PRIMARY KEY CLUSTERED 
(
	[NotificationId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[notification_status]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[notification_status](
	[NotificationStatusId] [int] IDENTITY(3,1) NOT NULL,
	[Name] [nvarchar](50) NULL DEFAULT (NULL),
 CONSTRAINT [PK_notification_status_NotificationStatusId] PRIMARY KEY CLUSTERED 
(
	[NotificationStatusId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[performance_center]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[performance_center](
	[PerformanceCenterId] [varchar](20) NOT NULL,
	[Description] [varchar](50) NULL,
	[ValueCenterId] [int] NULL,
	[IsActive] [bit] NULL CONSTRAINT [DF_performance_center_ISActive]  DEFAULT ((1)),
 CONSTRAINT [PK_performance_center] PRIMARY KEY CLUSTERED 
(
	[PerformanceCenterId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[plan]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[plan](
	[ClientProductId] [int] NOT NULL,
	[Price] [decimal](10, 2) NULL,
	[CampaignId] [int] NOT NULL,
	[January] [int] NULL,
	[February] [int] NULL,
	[March] [int] NULL,
	[Q1] [int] NULL,
	[April] [int] NULL,
	[May] [int] NULL,
	[June] [int] NULL,
	[Q2] [int] NULL,
	[July] [int] NULL,
	[August] [int] NULL,
	[September] [int] NULL,
	[Q3] [int] NULL,
	[October] [int] NULL,
	[November] [int] NULL,
	[December] [int] NULL,
	[Q4] [int] NULL,
	[Total] [int] NULL,
 CONSTRAINT [PK_plan_1] PRIMARY KEY CLUSTERED 
(
	[ClientProductId] ASC,
	[CampaignId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[sale]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[sale](
	[ClientId] [int] NOT NULL,
	[GmidId] [varchar](20) NOT NULL,
	[Month] [int] NOT NULL,
	[Amount] [int] NULL,
	[Total] [int] NULL,
	[CampaignId] [int] NOT NULL,
 CONSTRAINT [PK_sale] PRIMARY KEY CLUSTERED 
(
	[ClientId] ASC,
	[GmidId] ASC,
	[Month] ASC,
	[CampaignId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[setting]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[setting](
	[SettingId] [int] IDENTITY(3,1) NOT NULL,
	[Name] [varchar](150) NOT NULL,
	[DisplayName] [varchar](150) NOT NULL,
	[Value] [varchar](100) NOT NULL,
 CONSTRAINT [PK_setting_SettingId] PRIMARY KEY CLUSTERED 
(
	[SettingId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[state]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[state](
	[StateId] [int] IDENTITY(35,1) NOT NULL,
	[Name] [nvarchar](255) NOT NULL,
	[CountryId] [int] NOT NULL,
	[IsActive] [binary](1) NOT NULL,
 CONSTRAINT [PK_state_StateId] PRIMARY KEY CLUSTERED 
(
	[StateId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [state\$UkName] UNIQUE NONCLUSTERED 
(
	[Name] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[trade_product]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[trade_product](
	[TradeProductId] [varchar](20) NOT NULL,
	[Description] [varchar](100) NULL,
	[PerformanceCenterId] [varchar](20) NULL,
	[Price] [decimal](10, 2) NULL,
	[Profit] [decimal](10, 2) NULL,
	[IsForecastable] [bit] NULL CONSTRAINT [DF_trade_product_IsForecast]  DEFAULT ((1)),
	[IsActive] [bit] NULL CONSTRAINT [DF_trade_product_IsActive]  DEFAULT ((1)),
 CONSTRAINT [PK_trade_product] PRIMARY KEY CLUSTERED 
(
	[TradeProductId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[type_import]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[type_import](
	[TypeImportId] [int] IDENTITY(1,1) NOT NULL,
	[Name] [varchar](50) NOT NULL,
 CONSTRAINT [PK_type_import] PRIMARY KEY CLUSTERED 
(
	[TypeImportId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[unit]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

CREATE TABLE [dbo].[unit](
	[UnitId] [int] IDENTITY(1,1) NOT NULL,
	[Name] [nvarchar](10) NULL,
 CONSTRAINT [PK_unit_UnitId] PRIMARY KEY CLUSTERED 
(
	[UnitId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


/****** Object:  Table [dbo].[user]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[user](
	[UserId] [int] IDENTITY(5,1) NOT NULL,
	[DowUserId] [varchar](10) NULL,
	[Username] [varchar](255) NOT NULL,
	[Fullname] [varchar](255) NULL CONSTRAINT [DF__user__Fullname__4E88ABD4]  DEFAULT (NULL),
	[AuthKey] [varchar](100) NULL CONSTRAINT [DF__user__AuthKey__4F7CD00D]  DEFAULT (NULL),
	[PasswordHash] [varchar](255) NOT NULL,
	[PasswordResetToken] [varchar](255) NULL CONSTRAINT [DF__user__PasswordRe__5070F446]  DEFAULT (NULL),
	[Email] [varchar](255) NOT NULL,
	[ParentId] [int] NULL CONSTRAINT [DF__user__ParentId__5165187F]  DEFAULT (NULL),
	[CreatedAt] [datetime2](0) NULL CONSTRAINT [DF__user__CreatedAt__52593CB8]  DEFAULT (NULL),
	[UpdatedAt] [datetime2](0) NULL CONSTRAINT [DF__user__UpdatedAt__534D60F1]  DEFAULT (NULL),
	[resetPassword] [bit] NOT NULL CONSTRAINT [DF__user__resetPassw__5441852A]  DEFAULT ((0)),
	[IsActive] [bit] NOT NULL CONSTRAINT [DF__user__IsActive__5535A963]  DEFAULT ((1)),
 CONSTRAINT [PK_user_UserId] PRIMARY KEY CLUSTERED 
(
	[UserId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

/****** Object:  Table [dbo].[value_center]    Script Date: 24.8.2015 13:07:05 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[value_center](
	[ValueCenterId] [int] NOT NULL,
	[Description] [varchar](50) NOT NULL,
	[Abbreviation] [varchar](5) NULL,
	[IsActive] [bit] NULL CONSTRAINT [DF_value_center_IsActive]  DEFAULT ((1)),
 CONSTRAINT [PK_value_center] PRIMARY KEY CLUSTERED 
(
	[ValueCenterId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


CREATE TABLE [dbo].[lock_forecast](
	[LockId] [int] IDENTITY(1,1) NOT NULL,
	[DateFrom] [datetime] NOT NULL,
	[DateTo] [datetime] NOT NULL,
 CONSTRAINT [PK_lock_forecast] PRIMARY KEY CLUSTERED 
(
	[LockId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [dbo].[type_audit](
	[TypeAuditId] [int] IDENTITY(1,1) NOT NULL,
	[Name] [varchar](150) NULL,
	[PublicName] [varchar](150) NULL,
 CONSTRAINT [PK_type_audit] PRIMARY KEY CLUSTERED 
(
	[TypeAuditId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [dbo].[audit](
	[AuditId] [int] IDENTITY(1,1) NOT NULL,
	[TypeAuditId] [int] NULL,
	[CampaignId] [int] NULL,
	[UserId] [int] NULL,
	[ClientId] [int] NULL,
	[Description] [varchar](255) NULL,
	[Date] [datetime] NULL,
 CONSTRAINT [PK_audit] PRIMARY KEY CLUSTERED 
(
	[AuditId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

ALTER TABLE [dbo].[auth_rule] ADD  DEFAULT (NULL) FOR [created_at]

ALTER TABLE [dbo].[auth_rule] ADD  DEFAULT (NULL) FOR [updated_at]

ALTER TABLE [dbo].[city] ADD  DEFAULT (0x01) FOR [IsActive]

ALTER TABLE [dbo].[notification] ADD  DEFAULT (NULL) FOR [FromUserId]

ALTER TABLE [dbo].[notification] ADD  DEFAULT (NULL) FOR [Description]

ALTER TABLE [dbo].[notification] ADD  DEFAULT (NULL) FOR [ToUserId]

ALTER TABLE [dbo].[notification] ADD  DEFAULT (NULL) FOR [ToProfileId]

ALTER TABLE [dbo].[notification] ADD  DEFAULT (NULL) FOR [ObjectId]

ALTER TABLE [dbo].[notification] ADD  DEFAULT (NULL) FOR [CreatedAt]

ALTER TABLE [dbo].[notification] ADD  DEFAULT (NULL) FOR [NotificationStatusId]

ALTER TABLE [dbo].[setting] ADD  DEFAULT (N'0') FOR [Name]

ALTER TABLE [dbo].[setting] ADD  DEFAULT (N'0') FOR [DisplayName]

ALTER TABLE [dbo].[setting] ADD  DEFAULT (N'0') FOR [Value]

ALTER TABLE [dbo].[state] ADD  DEFAULT (0x01) FOR [IsActive]

ALTER TABLE [dbo].[unit] ADD  DEFAULT (NULL) FOR [Name]

ALTER TABLE [dbo].[auth_assignment]  WITH CHECK ADD  CONSTRAINT [auth_assignment\$auth_assignment_ibfk_1] FOREIGN KEY([item_name])
REFERENCES [dbo].[auth_item] ([name])
ON UPDATE CASCADE
ON DELETE CASCADE

ALTER TABLE [dbo].[auth_assignment] CHECK CONSTRAINT [auth_assignment\$auth_assignment_ibfk_1]

ALTER TABLE [dbo].[auth_assignment]  WITH CHECK ADD  CONSTRAINT [auth_assignment\$FkAuthAssignment_UserId] FOREIGN KEY([user_id])
REFERENCES [dbo].[user] ([UserId])
ON UPDATE CASCADE
ON DELETE CASCADE

ALTER TABLE [dbo].[auth_assignment] CHECK CONSTRAINT [auth_assignment\$FkAuthAssignment_UserId]

ALTER TABLE [dbo].[auth_item]  WITH CHECK ADD  CONSTRAINT [auth_item\$auth_item_ibfk_1] FOREIGN KEY([rule_name])
REFERENCES [dbo].[auth_rule] ([name])
ON UPDATE CASCADE
ON DELETE SET NULL

ALTER TABLE [dbo].[auth_item] CHECK CONSTRAINT [auth_item\$auth_item_ibfk_1]

ALTER TABLE [dbo].[auth_item_child]  WITH NOCHECK ADD  CONSTRAINT [auth_item_child\$auth_item_child_ibfk_1] FOREIGN KEY([parent])
REFERENCES [dbo].[auth_item] ([name])
ON UPDATE CASCADE
ON DELETE CASCADE

ALTER TABLE [dbo].[auth_item_child] NOCHECK CONSTRAINT [auth_item_child\$auth_item_child_ibfk_1]

ALTER TABLE [dbo].[city]  WITH NOCHECK ADD  CONSTRAINT [city\$FkCity_StateId] FOREIGN KEY([StateId])
REFERENCES [dbo].[state] ([StateId])

ALTER TABLE [dbo].[city] NOCHECK CONSTRAINT [city\$FkCity_StateId]

ALTER TABLE [dbo].[client]  WITH NOCHECK ADD  CONSTRAINT [FK_client_client] FOREIGN KEY([ClientId])
REFERENCES [dbo].[client] ([ClientId])

ALTER TABLE [dbo].[client] NOCHECK CONSTRAINT [FK_client_client]

ALTER TABLE [dbo].[client]  WITH NOCHECK ADD  CONSTRAINT [FK_client_client_type] FOREIGN KEY([ClientTypeId])
REFERENCES [dbo].[client_type] ([ClientTypeId])

ALTER TABLE [dbo].[client] NOCHECK CONSTRAINT [FK_client_client_type]

ALTER TABLE [dbo].[client]  WITH NOCHECK ADD  CONSTRAINT [FK_client_country] FOREIGN KEY([CountryId])
REFERENCES [dbo].[country] ([CountryId])

ALTER TABLE [dbo].[client] NOCHECK CONSTRAINT [FK_client_country]

ALTER TABLE [dbo].[client_product]  WITH NOCHECK ADD  CONSTRAINT [FK_client_product_client] FOREIGN KEY([ClientId])
REFERENCES [dbo].[client] ([ClientId])

ALTER TABLE [dbo].[client_product] NOCHECK CONSTRAINT [FK_client_product_client]

ALTER TABLE [dbo].[client_product]  WITH NOCHECK ADD  CONSTRAINT [FK_client_product_gmid] FOREIGN KEY([GmidId])
REFERENCES [dbo].[gmid] ([GmidId])

ALTER TABLE [dbo].[client_product] NOCHECK CONSTRAINT [FK_client_product_gmid]

ALTER TABLE [dbo].[client_product]  WITH NOCHECK ADD  CONSTRAINT [FK_client_product_trade_product] FOREIGN KEY([TradeProductId])
REFERENCES [dbo].[trade_product] ([TradeProductId])

ALTER TABLE [dbo].[client_product] NOCHECK CONSTRAINT [FK_client_product_trade_product]

ALTER TABLE [dbo].[client_seller]  WITH CHECK ADD  CONSTRAINT [FK_client_seller_client] FOREIGN KEY([ClientId])
REFERENCES [dbo].[client] ([ClientId])

ALTER TABLE [dbo].[client_seller] CHECK CONSTRAINT [FK_client_seller_client]

ALTER TABLE [dbo].[client_seller]  WITH NOCHECK ADD  CONSTRAINT [FK_client_seller_user] FOREIGN KEY([SellerId])
REFERENCES [dbo].[user] ([UserId])

ALTER TABLE [dbo].[client_seller] NOCHECK CONSTRAINT [FK_client_seller_user]

ALTER TABLE [dbo].[country]  WITH NOCHECK ADD  CONSTRAINT [FK_country_country] FOREIGN KEY([Description])
REFERENCES [dbo].[country] ([Description])

ALTER TABLE [dbo].[country] NOCHECK CONSTRAINT [FK_country_country]

ALTER TABLE [dbo].[forecast]  WITH NOCHECK ADD  CONSTRAINT [FK_forecast_campaign] FOREIGN KEY([CampaignId])
REFERENCES [dbo].[campaign] ([CampaignId])

ALTER TABLE [dbo].[forecast] NOCHECK CONSTRAINT [FK_forecast_campaign]

ALTER TABLE [dbo].[forecast]  WITH NOCHECK ADD  CONSTRAINT [FK_forecast_client_product] FOREIGN KEY([ClientProductId])
REFERENCES [dbo].[client_product] ([ClientProductId])

ALTER TABLE [dbo].[forecast] NOCHECK CONSTRAINT [FK_forecast_client_product]

ALTER TABLE [dbo].[gmid]  WITH NOCHECK ADD  CONSTRAINT [FK_gmid_country] FOREIGN KEY([CountryId])
REFERENCES [dbo].[country] ([CountryId])

ALTER TABLE [dbo].[gmid] NOCHECK CONSTRAINT [FK_gmid_country]

ALTER TABLE [dbo].[gmid]  WITH NOCHECK ADD  CONSTRAINT [FK_gmid_trade_product] FOREIGN KEY([TradeProductId])
REFERENCES [dbo].[trade_product] ([TradeProductId])

ALTER TABLE [dbo].[gmid] NOCHECK CONSTRAINT [FK_gmid_trade_product]

ALTER TABLE [dbo].[import]  WITH CHECK ADD  CONSTRAINT [FK_type_import] FOREIGN KEY([TypeImportId])
REFERENCES [dbo].[type_import] ([TypeImportId])

ALTER TABLE [dbo].[import] CHECK CONSTRAINT [FK_type_import]

ALTER TABLE [dbo].[notification]  WITH NOCHECK ADD  CONSTRAINT [notification\$FKNotification_FromUserId] FOREIGN KEY([FromUserId])
REFERENCES [dbo].[user] ([UserId])

ALTER TABLE [dbo].[notification] NOCHECK CONSTRAINT [notification\$FKNotification_FromUserId]

ALTER TABLE [dbo].[notification]  WITH NOCHECK ADD  CONSTRAINT [notification\$FKNotification_NotificationStatusId] FOREIGN KEY([NotificationStatusId])
REFERENCES [dbo].[notification_status] ([NotificationStatusId])

ALTER TABLE [dbo].[notification] NOCHECK CONSTRAINT [notification\$FKNotification_NotificationStatusId]

ALTER TABLE [dbo].[notification]  WITH NOCHECK ADD  CONSTRAINT [notification\$FKNotification_ToProfileId] FOREIGN KEY([ToProfileId])
REFERENCES [dbo].[auth_item] ([name])

ALTER TABLE [dbo].[notification] NOCHECK CONSTRAINT [notification\$FKNotification_ToProfileId]

ALTER TABLE [dbo].[notification]  WITH NOCHECK ADD  CONSTRAINT [notification\$FKNotification_ToUserId] FOREIGN KEY([ToUserId])
REFERENCES [dbo].[user] ([UserId])

ALTER TABLE [dbo].[notification] NOCHECK CONSTRAINT [notification\$FKNotification_ToUserId]

ALTER TABLE [dbo].[performance_center]  WITH NOCHECK ADD  CONSTRAINT [FK_performance_center_value_center] FOREIGN KEY([ValueCenterId])
REFERENCES [dbo].[value_center] ([ValueCenterId])

ALTER TABLE [dbo].[performance_center] NOCHECK CONSTRAINT [FK_performance_center_value_center]

ALTER TABLE [dbo].[plan]  WITH NOCHECK ADD  CONSTRAINT [FK_plan_campaign] FOREIGN KEY([CampaignId])
REFERENCES [dbo].[campaign] ([CampaignId])

ALTER TABLE [dbo].[plan] NOCHECK CONSTRAINT [FK_plan_campaign]

ALTER TABLE [dbo].[plan]  WITH NOCHECK ADD  CONSTRAINT [FK_plan_client_product] FOREIGN KEY([ClientProductId])
REFERENCES [dbo].[client_product] ([ClientProductId])

ALTER TABLE [dbo].[plan] NOCHECK CONSTRAINT [FK_plan_client_product]

ALTER TABLE [dbo].[sale]  WITH NOCHECK ADD  CONSTRAINT [FK_sale_campaign] FOREIGN KEY([CampaignId])
REFERENCES [dbo].[campaign] ([CampaignId])

ALTER TABLE [dbo].[sale] NOCHECK CONSTRAINT [FK_sale_campaign]

ALTER TABLE [dbo].[sale]  WITH NOCHECK ADD  CONSTRAINT [FK_sale_client] FOREIGN KEY([ClientId])
REFERENCES [dbo].[client] ([ClientId])

ALTER TABLE [dbo].[sale] NOCHECK CONSTRAINT [FK_sale_client]

ALTER TABLE [dbo].[sale]  WITH NOCHECK ADD  CONSTRAINT [FK_sale_gmid] FOREIGN KEY([GmidId])
REFERENCES [dbo].[gmid] ([GmidId])

ALTER TABLE [dbo].[sale] NOCHECK CONSTRAINT [FK_sale_gmid]

ALTER TABLE [dbo].[trade_product]  WITH NOCHECK ADD  CONSTRAINT [FK_trade_product_performance_center] FOREIGN KEY([PerformanceCenterId])
REFERENCES [dbo].[performance_center] ([PerformanceCenterId])

ALTER TABLE [dbo].[trade_product] NOCHECK CONSTRAINT [FK_trade_product_performance_center]

ALTER TABLE [dbo].[user]  WITH NOCHECK ADD  CONSTRAINT [user\$FkUser_ParentId] FOREIGN KEY([ParentId])
REFERENCES [dbo].[user] ([UserId])

ALTER TABLE [dbo].[user] NOCHECK CONSTRAINT [user\$FkUser_ParentId]

ALTER TABLE [dbo].[audit]  WITH CHECK ADD  CONSTRAINT [FK_audit_client] FOREIGN KEY([ClientId])
REFERENCES [dbo].[client] ([ClientId])

ALTER TABLE [dbo].[audit] CHECK CONSTRAINT [FK_audit_client]

ALTER TABLE [dbo].[audit]  WITH CHECK ADD  CONSTRAINT [FK_audit_type_audit] FOREIGN KEY([TypeAuditId])
REFERENCES [dbo].[type_audit] ([TypeAuditId])

ALTER TABLE [dbo].[audit] CHECK CONSTRAINT [FK_audit_type_audit]

ALTER TABLE [dbo].[audit]  WITH CHECK ADD  CONSTRAINT [FK_audit_user] FOREIGN KEY([UserId])
REFERENCES [dbo].[user] ([UserId])

ALTER TABLE [dbo].[audit] CHECK CONSTRAINT [FK_audit_user]

EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'`dow.forecast`.auth_assignment' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'auth_assignment'

ALTER TABLE [dbo].[audit]  WITH CHECK ADD  CONSTRAINT [FK_audit_campaign] FOREIGN KEY([CampaignId])
REFERENCES [dbo].[campaign] ([CampaignId])


ALTER TABLE [dbo].[audit] CHECK CONSTRAINT [FK_audit_campaign]

";

    $this->execute($sql);
    }

    public function down()
    {
        echo "m150727_155909_Database cannot be reverted.\n";

        return false;
    }
    

}
