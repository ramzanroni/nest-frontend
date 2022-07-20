-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 19, 2022 at 12:11 PM
-- Server version: 10.3.34-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eon_bazar`
--

-- --------------------------------------------------------

--
-- Table structure for table `carton_list`
--

CREATE TABLE `carton_list` (
  `id` bigint(20) NOT NULL,
  `so` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `delivered` tinyint(4) NOT NULL COMMENT '0=no, 1=yes',
  `ctn_no` tinyint(4) NOT NULL,
  `qc` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=no, 1=yes',
  `inv` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `carton_list`
--

INSERT INTO `carton_list` (`id`, `so`, `userid`, `created_at`, `delivered`, `ctn_no`, `qc`, `inv`) VALUES
(1, 7, 8, '2022-07-04 15:52:47', 1, 1, 1, 2),
(2, 7, 8, '2022-07-04 15:52:50', 0, 2, 0, 0),
(7, 7, 8, '2022-07-17 18:51:24', 0, 3, 0, 0),
(8, 153439, 8, '2022-07-17 18:51:24', 0, 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `carton_list_details`
--

CREATE TABLE `carton_list_details` (
  `id` bigint(20) NOT NULL,
  `cid` bigint(20) NOT NULL COMMENT 'carton id',
  `stockid` varchar(20) NOT NULL,
  `qty` double NOT NULL,
  `delivered` tinyint(4) NOT NULL COMMENT '0=no, 1=yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `carton_list_details`
--

INSERT INTO `carton_list_details` (`id`, `cid`, `stockid`, `qty`, `delivered`) VALUES
(1, 1, '1001201030', 1, 1),
(2, 1, '1001201031', 1, 1),
(3, 2, '1001201030', 4, 0),
(4, 2, '1001201031', 5, 0),
(15, 7, '1001201030', 3, 0),
(16, 7, '1001201031', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `carton_status_details`
--

CREATE TABLE `carton_status_details` (
  `id` int(11) NOT NULL,
  `cid` int(11) NOT NULL COMMENT 'carton id',
  `sid` int(11) NOT NULL COMMENT 'carton status id',
  `uid` int(11) NOT NULL COMMENT 'user id',
  `note` varchar(255) NOT NULL COMMENT 'company name ...',
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `carton_status_details`
--

INSERT INTO `carton_status_details` (`id`, `cid`, `sid`, `uid`, `note`, `create_at`) VALUES
(1, 1, 1, 8, 'note', '2022-07-04 15:52:47'),
(2, 2, 1, 8, 'note', '2022-07-01 10:52:50'),
(4, 1, 2, 8, 'note', '2022-07-04 12:52:47'),
(5, 2, 2, 8, 'note', '2022-07-02 12:52:50'),
(6, 2, 3, 8, 'note', '2022-07-03 16:52:50'),
(12, 7, 1, 8, 'note', '2022-07-17 18:51:24'),
(13, 7, 2, 8, 'piked by 8', '2022-07-18 16:38:11'),
(14, 8, 1, 8, 'note', '2022-07-01 10:52:50');

-- --------------------------------------------------------

--
-- Table structure for table `carton_status_list`
--

CREATE TABLE `carton_status_list` (
  `id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `carton_status_list`
--

INSERT INTO `carton_status_list` (`id`, `order`, `description`) VALUES
(1, 1, 'Packed'),
(2, 2, 'Shipping'),
(3, 3, 'Deliverd'),
(4, 4, 'Returend');

-- --------------------------------------------------------

--
-- Table structure for table `debtorsmaster`
--

CREATE TABLE `debtorsmaster` (
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `cm_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `address1` varchar(255) NOT NULL DEFAULT '',
  `address2` varchar(255) NOT NULL DEFAULT '',
  `address3` varchar(255) NOT NULL DEFAULT '',
  `address4` varchar(255) NOT NULL DEFAULT '',
  `address5` varchar(255) NOT NULL DEFAULT '',
  `address6` varchar(255) NOT NULL DEFAULT '',
  `currcode` char(3) NOT NULL DEFAULT '',
  `salestype` char(2) NOT NULL DEFAULT '',
  `clientsince` datetime DEFAULT NULL,
  `holdreason` smallint(6) NOT NULL DEFAULT 0,
  `paymentterms` char(2) NOT NULL DEFAULT 'f',
  `discount` double NOT NULL DEFAULT 0,
  `pymtdiscount` double NOT NULL DEFAULT 0,
  `lastpaid` double NOT NULL DEFAULT 0,
  `lastpaiddate` datetime DEFAULT NULL,
  `creditlimit` double NOT NULL DEFAULT 1000,
  `invaddrbranch` tinyint(4) NOT NULL DEFAULT 0,
  `discountcode` char(2) NOT NULL DEFAULT '',
  `ediinvoices` tinyint(4) NOT NULL DEFAULT 0,
  `ediorders` tinyint(4) NOT NULL DEFAULT 0,
  `edireference` varchar(20) NOT NULL DEFAULT '',
  `editransport` varchar(5) NOT NULL DEFAULT 'email',
  `ediaddress` varchar(50) NOT NULL DEFAULT '',
  `ediserveruser` varchar(20) NOT NULL DEFAULT '',
  `ediserverpwd` varchar(20) NOT NULL DEFAULT '',
  `taxref` varchar(20) NOT NULL DEFAULT '',
  `customerpoline` tinyint(1) NOT NULL DEFAULT 0,
  `typeid` tinyint(4) NOT NULL DEFAULT 1,
  `customer_note` text DEFAULT NULL COMMENT 'Customers phone, car number etc infos are collected here those can be used for finding the customer in future',
  `custcatid1` smallint(6) DEFAULT 0 COMMENT 'Category/ Industry Type: personal, trader, garments, knitting, dying, textile etc',
  `op_bal` double NOT NULL DEFAULT 0,
  `phone1` varchar(255) NOT NULL,
  `phone2` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0=inactive,1=active',
  `bin_no` varchar(255) NOT NULL,
  `nid_no` varchar(255) NOT NULL,
  `vat` varchar(255) NOT NULL,
  `tin` varchar(255) NOT NULL,
  `ait_deduction` tinyint(4) NOT NULL,
  `ait_deduction_percent` double NOT NULL,
  `user_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `debtorsmaster`
--

INSERT INTO `debtorsmaster` (`debtorno`, `cm_id`, `name`, `address1`, `address2`, `address3`, `address4`, `address5`, `address6`, `currcode`, `salestype`, `clientsince`, `holdreason`, `paymentterms`, `discount`, `pymtdiscount`, `lastpaid`, `lastpaiddate`, `creditlimit`, `invaddrbranch`, `discountcode`, `ediinvoices`, `ediorders`, `edireference`, `editransport`, `ediaddress`, `ediserveruser`, `ediserverpwd`, `taxref`, `customerpoline`, `typeid`, `customer_note`, `custcatid1`, `op_bal`, `phone1`, `phone2`, `email`, `created_by`, `created_at`, `updated_at`, `updated_by`, `status`, `bin_no`, `nid_no`, `vat`, `tin`, `ait_deduction`, `ait_deduction_percent`, `user_token`) VALUES
('142', 142, 'sumon babu', 'Uttara, Dhaka', '', '', '', '', '', 'BDT', 'DP', '2022-07-02 00:00:00', 1, '30', 0, 0, 0, NULL, 10000, 0, '', 0, 0, '', 'email', '', '', '', '', 0, 1, '', 0, 0, '01737642007', '', '', 0, '2022-06-30 17:09:09', '2022-07-02 05:35:55', 0, 1, '', '', '', '', 0, 0, 'MDE3Mzc2NDIwMDcyMDIyLTA3LTAyIDA1OjI4OjM0'),
('143', 143, 'Rana rahman', 'Uttara, Dhaka', '', '', '', '', '', 'BDT', 'DP', '2022-07-02 05:49:02', 1, '30', 0, 0, 0, NULL, 10000, 0, '', 0, 0, '', 'email', '', '', '', '', 0, 1, NULL, 0, 0, '01712345678', '', '', 0, '2022-06-30 17:09:09', '0000-00-00 00:00:00', 0, 1, '', '', '', '', 0, 0, 'MDE3MTIzNDU2NzgyMDIyLTA3LTAyIDA1OjQ5OjAy'),
('144', 144, 'Roni', 'Uttara, Dhaka', '', '', '', '', '', 'BDT', 'DP', '2022-07-02 14:21:45', 1, '30', 0, 0, 0, NULL, 10000, 0, '', 0, 0, '', 'email', '', '', '', '', 0, 1, NULL, 0, 0, '01713015318', '', '', 0, '2022-06-30 17:09:09', '0000-00-00 00:00:00', 0, 1, '', '', '', '', 0, 0, 'MDE3MTMwMTUzMTgyMDIyLTA3LTAyIDE0OjIxOjQ1');

-- --------------------------------------------------------

--
-- Table structure for table `item_ref_file`
--

CREATE TABLE `item_ref_file` (
  `id` bigint(20) NOT NULL,
  `stockid` varchar(255) NOT NULL,
  `doc_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `salesorderdetails`
--

CREATE TABLE `salesorderdetails` (
  `orderlineno` int(11) NOT NULL DEFAULT 0,
  `orderno` int(11) NOT NULL DEFAULT 0,
  `stkcode` varchar(20) NOT NULL DEFAULT '',
  `qtyinvoiced` double NOT NULL DEFAULT 0,
  `unitprice` double NOT NULL DEFAULT 0,
  `quantity` double NOT NULL DEFAULT 0,
  `qtypaid` double NOT NULL DEFAULT 0,
  `tax_rate` double NOT NULL DEFAULT 0,
  `estimate` tinyint(4) NOT NULL DEFAULT 0,
  `discountpercent` double NOT NULL DEFAULT 0,
  `actualdispatchdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `narrative` text DEFAULT NULL,
  `itemdue` date DEFAULT NULL COMMENT 'Due date for line item.  Some customers require \r\nacknowledgements with due dates by line item',
  `poline` varchar(10) DEFAULT NULL COMMENT 'Some Customers require acknowledgements with a PO line number for each sales line',
  `route_id` varchar(20) DEFAULT NULL,
  `mo_isu_qty` double(15,3) NOT NULL DEFAULT 0.000,
  `mo_isu_seq_qty` double(15,3) NOT NULL DEFAULT 0.000,
  `route_stage` smallint(6) DEFAULT NULL,
  `mo_rcv_seq_qty` double(15,3) NOT NULL DEFAULT 0.000,
  `discount_amount` double NOT NULL,
  `discount_flag` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=flat,2=percen',
  `org_so_qty` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salesorderdetails`
--

INSERT INTO `salesorderdetails` (`orderlineno`, `orderno`, `stkcode`, `qtyinvoiced`, `unitprice`, `quantity`, `qtypaid`, `tax_rate`, `estimate`, `discountpercent`, `actualdispatchdate`, `completed`, `narrative`, `itemdue`, `poline`, `route_id`, `mo_isu_qty`, `mo_isu_seq_qty`, `route_stage`, `mo_rcv_seq_qty`, `discount_amount`, `discount_flag`, `org_so_qty`) VALUES
(1, 7, '1001201030', 0, 123, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(1, 129776, '1001201030', 0, 123, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(1, 153439, '100902', 0, 111, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(1, 312714, '100902', 0, 111, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(1, 454689, '100902', 0, 111, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(1, 845592, '100902', 0, 111, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 3, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(2, 7, '1001201031', 0, 13, 3, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(2, 129776, '1001201031', 0, 13, 3, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(2, 153439, '100906', 0, 7896, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(2, 454689, '1009', 0, 44, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(2, 845592, '1005', 0, 78, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 3, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(3, 454689, '100901', 0, 124, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(3, 845592, '1002', 0, 1097, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 3, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0),
(4, 845592, '1006', 0, 7878, 1, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, NULL, NULL, NULL, NULL, 0.000, 0.000, NULL, 0.000, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `salesorders`
--

CREATE TABLE `salesorders` (
  `orderno` int(11) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `customerref` varchar(50) NOT NULL DEFAULT '',
  `buyername` varchar(50) DEFAULT NULL,
  `tag` int(11) NOT NULL DEFAULT 1,
  `comments` text DEFAULT NULL,
  `orddate` date DEFAULT NULL,
  `ordertype` char(2) NOT NULL DEFAULT '',
  `shipvia` int(11) NOT NULL DEFAULT 0,
  `deladd1` varchar(255) NOT NULL DEFAULT '',
  `deladd2` varchar(255) NOT NULL DEFAULT '',
  `deladd3` varchar(255) NOT NULL DEFAULT '',
  `deladd4` varchar(255) DEFAULT NULL,
  `deladd5` varchar(255) NOT NULL DEFAULT '',
  `deladd6` varchar(255) NOT NULL DEFAULT '',
  `contactphone` varchar(25) DEFAULT NULL,
  `contactemail` varchar(40) DEFAULT NULL,
  `deliverto` varchar(40) NOT NULL DEFAULT '',
  `deliverblind` tinyint(1) DEFAULT 1,
  `freightcost` double NOT NULL DEFAULT 0,
  `fromstkloc` varchar(5) NOT NULL DEFAULT '',
  `deliverydate` date DEFAULT NULL,
  `quotedate` date NOT NULL DEFAULT '0000-00-00',
  `confirmeddate` date NOT NULL DEFAULT '0000-00-00',
  `printedpackingslip` tinyint(4) NOT NULL DEFAULT 0,
  `datepackingslipprinted` date NOT NULL DEFAULT '0000-00-00',
  `quotation` tinyint(4) NOT NULL DEFAULT 0,
  `assigned` tinyint(4) NOT NULL DEFAULT 0,
  `salesmancode` varchar(6) DEFAULT NULL,
  `so_currency` char(3) DEFAULT NULL,
  `so_exchange_rate` double DEFAULT NULL COMMENT 'Negotiated Exchange Rate for Specific SO',
  `approved_for_production` tinyint(4) NOT NULL DEFAULT 0,
  `so_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=normal,1=NeoPI',
  `so_status` tinyint(4) NOT NULL DEFAULT 0,
  `delivery_status` tinyint(4) NOT NULL COMMENT '0=no,1=partial,2=full',
  `issue_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salesorders`
--

INSERT INTO `salesorders` (`orderno`, `debtorno`, `branchcode`, `customerref`, `buyername`, `tag`, `comments`, `orddate`, `ordertype`, `shipvia`, `deladd1`, `deladd2`, `deladd3`, `deladd4`, `deladd5`, `deladd6`, `contactphone`, `contactemail`, `deliverto`, `deliverblind`, `freightcost`, `fromstkloc`, `deliverydate`, `quotedate`, `confirmeddate`, `printedpackingslip`, `datepackingslipprinted`, `quotation`, `assigned`, `salesmancode`, `so_currency`, `so_exchange_rate`, `approved_for_production`, `so_type`, `so_status`, `delivery_status`, `issue_date`) VALUES
(7, '4', '1', 'SO:1', NULL, 1, 'Nothing', '2022-07-17', 'DP', 1, 'Shamim saroni', '', '', NULL, '', '', '017167171', NULL, 'main', 1, 0, '1010', NULL, '0000-00-00', '0000-00-00', 0, '0000-00-00', 0, 0, NULL, NULL, NULL, 0, 0, 1, 0, '2022-07-17'),
(129776, '4', '1', 'SO:1', NULL, 1, 'Nothing', '2022-07-19', 'DP', 1, 'Shamim saroni', '', '', NULL, '', '', '017167171', NULL, 'main', 1, 0, '1010', NULL, '0000-00-00', '0000-00-00', 0, '0000-00-00', 0, 0, NULL, NULL, NULL, 0, 0, 3, 0, '2022-07-19'),
(153439, '4', '1', 'SO:1', NULL, 1, '', '2022-07-19', 'DP', 1, 'Dhaka', '', '', NULL, '', '', '', NULL, 'main', 1, 0, '1010', NULL, '0000-00-00', '0000-00-00', 0, '0000-00-00', 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, '2022-07-19'),
(312714, '4', '1', 'SO:1', NULL, 1, '', '2022-07-19', 'DP', 1, 'Dhaka', '', '', NULL, '', '', '', NULL, 'main', 1, 0, '1010', NULL, '0000-00-00', '0000-00-00', 0, '0000-00-00', 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, '2022-07-19'),
(454689, '4', '1', 'SO:1', NULL, 1, '', '2022-07-19', 'DP', 1, 'Dhaka', '', '', NULL, '', '', '', NULL, 'main', 1, 0, '1010', NULL, '0000-00-00', '0000-00-00', 0, '0000-00-00', 0, 0, NULL, NULL, NULL, 0, 0, 2, 0, '2022-07-19'),
(845592, '4', '1', 'SO:1', NULL, 1, '', '2022-07-19', 'DP', 1, 'Dhaka', '', '', NULL, '', '', '', NULL, 'main', 1, 0, '1010', NULL, '0000-00-00', '0000-00-00', 0, '0000-00-00', 0, 0, NULL, NULL, NULL, 0, 0, 1, 0, '2022-07-19');

-- --------------------------------------------------------

--
-- Table structure for table `stockgroup`
--

CREATE TABLE `stockgroup` (
  `groupid` int(11) NOT NULL,
  `groupname` varchar(255) NOT NULL DEFAULT '',
  `parent` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL,
  `web` int(11) NOT NULL DEFAULT 0 COMMENT 'show in web app'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stockgroup`
--

INSERT INTO `stockgroup` (`groupid`, `groupname`, `parent`, `image`, `web`) VALUES
(1, 'Undefined', 0, 'images/category-1.svg', 1),
(2, 'Trims &amp; Accessories', 0, 'images/category-1.svg', 1),
(3, 'Fabrics', 0, 'images/category-1.svg', 1),
(4, 'Dyes &amp; Chems', 0, 'images/category-1.svg', 0),
(5, 'Yarns', 0, 'images/category-1.svg', 1),
(6, 'Others', 0, 'images/category-1.svg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stockmaster`
--

CREATE TABLE `stockmaster` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `code` varchar(255) NOT NULL,
  `categoryid` varchar(6) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `longdescription` text NOT NULL,
  `units` varchar(20) NOT NULL DEFAULT 'each',
  `mbflag` char(1) NOT NULL DEFAULT 'B',
  `lastcurcostdate` date NOT NULL DEFAULT '1800-01-01',
  `actualcost` double NOT NULL DEFAULT 0,
  `lastcost` double NOT NULL DEFAULT 0,
  `materialcost` double NOT NULL DEFAULT 0,
  `labourcost` double NOT NULL DEFAULT 0,
  `overheadcost` double NOT NULL DEFAULT 0,
  `lowestlevel` smallint(6) NOT NULL DEFAULT 0,
  `discontinued` tinyint(4) NOT NULL DEFAULT 0,
  `controlled` tinyint(4) NOT NULL DEFAULT 0,
  `eoq` double NOT NULL DEFAULT 0,
  `volume` decimal(20,4) NOT NULL DEFAULT 0.0000,
  `kgs` decimal(20,4) NOT NULL DEFAULT 0.0000,
  `barcode` varchar(50) NOT NULL DEFAULT '',
  `discountcategory` char(2) NOT NULL DEFAULT '',
  `taxcatid` tinyint(4) NOT NULL DEFAULT 1,
  `taxcatp` int(11) NOT NULL DEFAULT 1,
  `serialised` tinyint(4) NOT NULL DEFAULT 0,
  `appendfile` varchar(40) NOT NULL DEFAULT 'none',
  `perishable` tinyint(1) NOT NULL DEFAULT 0,
  `decimalplaces` tinyint(4) NOT NULL DEFAULT 0,
  `nextserialno` bigint(20) NOT NULL DEFAULT 0,
  `pansize` double NOT NULL DEFAULT 0,
  `shrinkfactor` double NOT NULL DEFAULT 0,
  `netweight` decimal(20,4) NOT NULL DEFAULT 0.0000,
  `productiontime` tinyint(4) NOT NULL DEFAULT 0,
  `webitem` char(1) DEFAULT '0',
  `depreciation_rate` double(15,3) DEFAULT NULL COMMENT 'In Percentage',
  `depreciation_run` tinyint(4) DEFAULT NULL COMMENT 'Checking value in this field will add this item code items to be include in run depreciation command',
  `addncalist` tinyint(4) DEFAULT NULL COMMENT 'Add to NCA list / Add to fixed asset list',
  `brandid` int(11) NOT NULL DEFAULT 1,
  `groupid` int(11) NOT NULL DEFAULT 1,
  `subgroupid` int(11) NOT NULL DEFAULT 1,
  `webprice` double DEFAULT NULL,
  `updatetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tag` int(11) DEFAULT 1,
  `wip_qty` double DEFAULT 0,
  `wip_cost` double DEFAULT 0,
  `stock_gl` varchar(255) DEFAULT NULL,
  `op_bal` double NOT NULL DEFAULT 0,
  `op_qty` double NOT NULL DEFAULT 0,
  `size` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `style` varchar(255) NOT NULL,
  `depreciation_act` int(11) NOT NULL,
  `default_store` varchar(255) NOT NULL DEFAULT '0',
  `portable` tinyint(4) NOT NULL COMMENT '1=yes',
  `length` double NOT NULL,
  `length_unit` tinyint(4) NOT NULL COMMENT '1=cm,2=inch',
  `giid` bigint(20) NOT NULL,
  `hs_code` varchar(20) NOT NULL,
  `tarrif_value` double NOT NULL,
  `wip_wtd_rate` double NOT NULL,
  `pz` tinyint(4) NOT NULL,
  `default_expiry_date` tinyint(4) NOT NULL COMMENT '0=none,1=30days,2=90days,3=6months,4=1years,5=3years,6=5years',
  `pzCons` double NOT NULL,
  `mz` tinyint(4) NOT NULL,
  `mzCons` double NOT NULL,
  `min_cm` double NOT NULL,
  `cm_base_price` double NOT NULL,
  `min_inc` double NOT NULL,
  `inc_base_price` double NOT NULL,
  `cm_addi_price` double NOT NULL,
  `inc_addi_price` double NOT NULL,
  `per_carton_qty` double NOT NULL,
  `cost_` double NOT NULL,
  `img` varchar(255) NOT NULL,
  `est_cost` double NOT NULL,
  `item_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=simple,2=variation',
  `parent_id` int(11) NOT NULL,
  `dia` varchar(20) NOT NULL,
  `temp_bom_set` bigint(20) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=297 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stockmaster`
--

INSERT INTO `stockmaster` (`stockid`, `code`, `categoryid`, `description`, `longdescription`, `units`, `mbflag`, `lastcurcostdate`, `actualcost`, `lastcost`, `materialcost`, `labourcost`, `overheadcost`, `lowestlevel`, `discontinued`, `controlled`, `eoq`, `volume`, `kgs`, `barcode`, `discountcategory`, `taxcatid`, `taxcatp`, `serialised`, `appendfile`, `perishable`, `decimalplaces`, `nextserialno`, `pansize`, `shrinkfactor`, `netweight`, `productiontime`, `webitem`, `depreciation_rate`, `depreciation_run`, `addncalist`, `brandid`, `groupid`, `subgroupid`, `webprice`, `updatetime`, `tag`, `wip_qty`, `wip_cost`, `stock_gl`, `op_bal`, `op_qty`, `size`, `color`, `style`, `depreciation_act`, `default_store`, `portable`, `length`, `length_unit`, `giid`, `hs_code`, `tarrif_value`, `wip_wtd_rate`, `pz`, `default_expiry_date`, `pzCons`, `mz`, `mzCons`, `min_cm`, `cm_base_price`, `min_inc`, `inc_base_price`, `cm_addi_price`, `inc_addi_price`, `per_carton_qty`, `cost_`, `img`, `est_cost`, `item_type`, `parent_id`, `dia`, `temp_bom_set`) VALUES
('1002', '1002', '1', 'Wood Pencil Blue', 'Wood Pencil Blue', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '1', '', 6, 7, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, '0', 0.000, 0, 0, 1, 1, 2, 1097, '2022-05-14 12:11:35', 1, 0, 0, '', 0, 0, '0', '0', '0', 0, '0', 0, 0, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/50 ton Sheet Metal Press.jpg', 0, 1, 0, '', 0),
('1005', '1005', '1', 'Pencil Sharpener Small', 'Pencil Sharpener Small', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 78, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/Brazing Machine.jpg', 0, 1, 0, '', 0),
('1006', '1006', '1', 'Pencil Sharpener Desktop', 'Pencil Sharpener Desktop', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 7878, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/Circular Knitting Machine.jpg', 0, 1, 0, '', 0),
('1008', '1008', '1', 'File Cover Typ 1', 'File Cover Typ 1', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 2, 78, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/Engine Lathe Machine 5.jpg', 0, 1, 0, '', 0),
('1009', '1009', '1', 'File Cover Soft Type 2', 'File Cover Soft Type 2', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 2, 44, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/Grip Plier 8 inch.jpg', 0, 1, 0, '', 0),
('100901', '100901', '1', 'Marker Permanent', 'Marker Permanent', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 124, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/Monkey Plier 10 inch.jpg', 0, 1, 0, '', 0),
('100902', '100902', '2', 'Whiteboard Marker', 'Whiteboard Marker', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 111, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/Nose Plier 8 inch.jpg', 0, 1, 0, '', 0),
('100904', '100904', '1', 'Highlight Marker', 'Highlight Marker', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 1233, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/Slip join Plier 7 inch.jpg', 0, 1, 0, '', 0),
('100905', '100905', '1', 'Pilot V5 Hi-Tecpoint 0.5mm Black', 'Pilot V5 Hi-Tecpoint 0.5mm Black', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 4, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/Spay Painting Machine.jpg', 0, 1, 0, '', 0),
('100906', '100906', '2', 'Staedtler Mars Carbon 200-B Mechanical Pencil Lead Refills', 'Staedtler Mars Carbon 200-B Mechanical Pencil Lead Refills', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 7896, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'images/Tube forming Machine.jpg', 0, 1, 0, '', 0),
('1010', '1010', '1', 'Stapler Big', 'Stapler Big', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 4, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 1, 0, '', 0),
('1020', '1020', '1', 'Stapler Big type 2', 'Stapler Big type 2', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 4, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 1, 0, '', 0),
('1030', '1030', '1', 'Stapler Heavy Duty', 'Stapler Heavy Duty', 'pcs', 'B', '1800-01-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '', '', 5, 0, 0, '', 0, 0, 0, 0, 0, '0.0000', 0, NULL, 0.000, 0, 0, 1, 1, 1, 78, '0000-00-00 00:00:00', 1, 0, 0, NULL, 0, 0, '0', '0', '0', 0, '0', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 1, 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `temp_otp`
--

CREATE TABLE `temp_otp` (
  `id` int(255) NOT NULL,
  `phone` varchar(222) NOT NULL,
  `otp` int(20) NOT NULL,
  `expair_at` datetime NOT NULL,
  `counter` int(200) NOT NULL,
  `flag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `userid` varchar(222) NOT NULL,
  `password` varchar(255) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `customerid` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address1` text NOT NULL,
  `active_now` varchar(255) NOT NULL DEFAULT '1',
  `user_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userid`, `password`, `realname`, `customerid`, `phone`, `email`, `address1`, `active_now`, `user_token`) VALUES
(4, '', '', 'Ramzan', '', '01767270653', 'ramzan@gmail.com', 'Dhaka', '1', 'MDE3NjcyNzA2NTMyMDIyLTA3LTE3IDE3OjE5OjIz'),
(5, '', '', 'ridhoy', '', '01516158298', '', 'Dhaka', '1', 'MDE1MTYxNTgyOTgyMDIyLTA3LTE4IDE2OjIyOjQ0'),
(6, '', '', 'Sumon', '', '01737642007', '', 'Babu', '1', 'MDE3Mzc2NDIwMDcyMDIyLTA3LTE4IDE4OjMyOjUx');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carton_list`
--
ALTER TABLE `carton_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `so` (`so`);

--
-- Indexes for table `carton_list_details`
--
ALTER TABLE `carton_list_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cid` (`cid`),
  ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `carton_status_details`
--
ALTER TABLE `carton_status_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carton_status_list`
--
ALTER TABLE `carton_status_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `debtorsmaster`
--
ALTER TABLE `debtorsmaster`
  ADD PRIMARY KEY (`debtorno`),
  ADD KEY `Currency` (`currcode`),
  ADD KEY `HoldReason` (`holdreason`),
  ADD KEY `PaymentTerms` (`paymentterms`),
  ADD KEY `SalesType` (`salestype`),
  ADD KEY `EDIInvoices` (`ediinvoices`),
  ADD KEY `EDIOrders` (`ediorders`),
  ADD KEY `debtorsmaster_ibfk_5` (`typeid`),
  ADD KEY `custcatid1` (`custcatid1`);

--
-- Indexes for table `item_ref_file`
--
ALTER TABLE `item_ref_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salesorderdetails`
--
ALTER TABLE `salesorderdetails`
  ADD PRIMARY KEY (`orderlineno`,`orderno`),
  ADD KEY `OrderNo` (`orderno`),
  ADD KEY `StkCode` (`stkcode`),
  ADD KEY `Completed` (`completed`);

--
-- Indexes for table `salesorders`
--
ALTER TABLE `salesorders`
  ADD PRIMARY KEY (`orderno`),
  ADD KEY `DebtorNo` (`debtorno`),
  ADD KEY `OrdDate` (`orddate`),
  ADD KEY `OrderType` (`ordertype`),
  ADD KEY `LocationIndex` (`fromstkloc`),
  ADD KEY `BranchCode` (`branchcode`,`debtorno`),
  ADD KEY `ShipVia` (`shipvia`),
  ADD KEY `quotation` (`quotation`),
  ADD KEY `fk_salesmancode` (`salesmancode`),
  ADD KEY `FK_salesorders_tags` (`tag`);

--
-- Indexes for table `stockgroup`
--
ALTER TABLE `stockgroup`
  ADD PRIMARY KEY (`groupid`),
  ADD UNIQUE KEY `groupname` (`groupname`);

--
-- Indexes for table `stockmaster`
--
ALTER TABLE `stockmaster`
  ADD PRIMARY KEY (`stockid`),
  ADD UNIQUE KEY `description_2` (`description`),
  ADD KEY `CategoryID` (`categoryid`),
  ADD KEY `Description` (`description`),
  ADD KEY `LastCurCostDate` (`lastcurcostdate`),
  ADD KEY `MBflag` (`mbflag`),
  ADD KEY `StockID` (`stockid`,`categoryid`),
  ADD KEY `Controlled` (`controlled`),
  ADD KEY `DiscountCategory` (`discountcategory`),
  ADD KEY `taxcatid` (`taxcatid`);

--
-- Indexes for table `temp_otp`
--
ALTER TABLE `temp_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carton_list`
--
ALTER TABLE `carton_list`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `carton_list_details`
--
ALTER TABLE `carton_list_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `carton_status_details`
--
ALTER TABLE `carton_status_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `carton_status_list`
--
ALTER TABLE `carton_status_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item_ref_file`
--
ALTER TABLE `item_ref_file`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stockgroup`
--
ALTER TABLE `stockgroup`
  MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `temp_otp`
--
ALTER TABLE `temp_otp`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
