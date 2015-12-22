
--
-- Table structure for table `batch_items`
--

DROP TABLE IF EXISTS `batch_items`;
CREATE TABLE IF NOT EXISTS `batch_items` (
`batchitem_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `unit` varchar(64) NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `name` varchar(1024) DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  `origin` varchar(1024) DEFAULT NULL,
  `vendor` varchar(1024) DEFAULT NULL,
  `vendorpartno` varchar(64) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Indexes for table `batch_items`
--
ALTER TABLE `batch_items`
 ADD PRIMARY KEY (`batchitem_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batch_items`
--
ALTER TABLE `batch_items`
MODIFY `batchitem_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;