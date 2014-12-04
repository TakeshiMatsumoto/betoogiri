<?php defined('COREPATH') or exit('No direct script access allowed'); ?>

WARNING - 2014-10-17 06:52:13 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2014-10-17 06:52:19 --> Notice - Use of undefined constant user_name - assumed 'user_name' in C:\xampp\test_fuelphp\fuel\app\classes\controller\vote.php on line 64
WARNING - 2014-10-17 06:52:42 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2014-10-17 06:52:42 --> 23000 - SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'question_num' in where clause is ambiguous with query: "SELECT SUM(point) FROM `votelist` INNER JOIN `groupanswerlist` ON (`votelist`.`answer_num` = `groupanswerlist`.`id`) WHERE `question_num` = '1' GROUP BY `answer_num`" in C:\xampp\test_fuelphp\fuel\core\classes\database\pdo\connection.php on line 270
WARNING - 2014-10-17 06:53:10 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 06:54:19 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2014-10-17 06:54:19 --> 23000 - SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'user_name' in field list is ambiguous with query: "SELECT SUM(point), `user_name` FROM `votelist` INNER JOIN `groupanswerlist` ON (`votelist`.`answer_num` = `groupanswerlist`.`id`) WHERE `votelist`.`question_num` = '1' GROUP BY `answer_num`" in C:\xampp\test_fuelphp\fuel\core\classes\database\pdo\connection.php on line 270
WARNING - 2014-10-17 06:54:41 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 06:55:12 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 10:17:10 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2014-10-17 10:17:10 --> 1054 - SQLSTATE[42S22]: Column not found: 1054 Unknown column 'desc' in 'order clause' with query: "SELECT SUM(point) as point, `groupanswerlist`.`user_name` FROM `votelist` INNER JOIN `groupanswerlist` ON (`votelist`.`answer_num` = `groupanswerlist`.`id`) WHERE `votelist`.`question_num` = '1' GROUP BY `answer_num` ORDER BY `desc`" in C:\xampp\test_fuelphp\fuel\core\classes\database\pdo\connection.php on line 270
WARNING - 2014-10-17 10:18:35 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2014-10-17 10:18:35 --> 1054 - SQLSTATE[42S22]: Column not found: 1054 Unknown column 'SUM(point)' in 'order clause' with query: "SELECT SUM(point) as point, `groupanswerlist`.`user_name` FROM `votelist` INNER JOIN `groupanswerlist` ON (`votelist`.`answer_num` = `groupanswerlist`.`id`) WHERE `votelist`.`question_num` = '1' GROUP BY `answer_num` ORDER BY `SUM(point)` DESC" in C:\xampp\test_fuelphp\fuel\core\classes\database\pdo\connection.php on line 270
WARNING - 2014-10-17 10:19:40 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 12:06:27 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2014-10-17 12:06:27 --> 1054 - SQLSTATE[42S22]: Column not found: 1054 Unknown column '' in 'field list' with query: "SELECT SUM(point) as total_point, `votelist`.`answer_num`, `groupanswerlist`.`user_name`, `` FROM `votelist` INNER JOIN `groupanswerlist` ON (`votelist`.`answer_num` = `groupanswerlist`.`id`) INNER JOIN `questionlist` ON (`questionlist`.`id` = `groupanswerlist`.`question_num`) WHERE `votelist`.`question_num` = '1' GROUP BY `answer_num` ORDER BY `total_point` DESC" in C:\xampp\test_fuelphp\fuel\core\classes\database\pdo\connection.php on line 270
WARNING - 2014-10-17 12:12:22 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 12:14:35 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 12:50:58 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 12:51:08 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 12:51:12 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2014-10-17 12:51:12 --> Notice - Undefined index: totalpoint in C:\xampp\test_fuelphp\fuel\app\classes\controller\vote.php on line 82
WARNING - 2014-10-17 12:52:57 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2014-10-17 12:52:58 --> 1054 - SQLSTATE[42S22]: Column not found: 1054 Unknown column 'rank' in 'field list' with query: "INSERT INTO `votelist` (`rank`, `username`, `question`, `answer_num`, `totalpoint`) VALUES (1, 'yama', 'testtesttesttest', '2', '52')" in C:\xampp\test_fuelphp\fuel\core\classes\database\pdo\connection.php on line 270
WARNING - 2014-10-17 12:55:23 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 12:58:36 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2014-10-17 13:00:58 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2014-10-17 13:00:58 --> 1054 - SQLSTATE[42S22]: Column not found: 1054 Unknown column 'answer' in 'field list' with query: "INSERT INTO `groupquestionresult` (`rank`, `username`, `answer`, `question`, `answer_num`, `totalpoint`) VALUES (1, 'yama', '???', 'testtesttesttest', '2', '65')" in C:\xampp\test_fuelphp\fuel\core\classes\database\pdo\connection.php on line 270
WARNING - 2014-10-17 13:02:28 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
