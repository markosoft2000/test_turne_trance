# test_turne_trance

Задание на PHP + MySql

Система бронирования авиабилетов имеет вэб-интерфейс. В систему бронирования отправляется запрос, возвращается ответ в виде xml.

Задача: принять xml-ответ и распарсить его, получить аэропорты вылета (From="DME"), прилёта (To="OVB"), время вылета и прилёта (Time="2013-06-05T10:00:00+04:00"), перевозчик (Airline="U6"), номер рейса (Flight="355"), количество пассажиров (AgeCat="ADT" Count="2"), общую цену (Price Total="37704.00") и сохранить в таблице `flights` базы данных MySql.

в тестовом примере task_xml.txt приведён реальный ответ системы, который содержит два перелёта <ShopOption> (в примере они абсолютно одинаковые), состоящий из двух частей <ItineraryOption> - туда и обратно. в каждой части два сегмента перелёта <FlightSegment> (перелёт с пересадкой). Информация о пассажирах и по ценам находится в тэге <FareInfo>. Летит три пассажира: 2 взрослых и младенец <PaxType AgeCat="ADT" Count="2"/>, где ADT - adult (взрослый), CLD - child (ребёнок), INF - infant (младенец). Полная цена на категорию пассажира указана в <Price Total="37704.00">
коды аэропортов приходят в виде трёхбуквенных значений, а в таблице надо сохранить их id-шник из справочника `airports`. соответственно, надо осуществить выборку из БД по коду

Оформить в виде класса. Ответ должен выглядеть как:

$searchFlight = new searchFlight();
$searchFlight->send_and_save();

- написать содержимое класса searchFlight

использовать можно любые существующие наработки и сторонние классы
имитировать запрос к системе бронирования - просто открыть и считать файл task_xml.txt
так как в файле два варианта перелёта, то и вставка должна быть двух строк в БД

реальную работу не надо демонстрировать (баги отлавливать не нужно), код на исполнимость проверяться не будет. можно написать "на коленке", смотреться будут идеи и реализация. тем не менее, код должен выглядеть рабочим механизмом, требующим отладки и доводки, а не схемой алгоритма, под которую ещё требуется написать кучу методов, в ходе написания которых выяснится, что реализация будет совсем другой. таблицу flight требуется заполнить все 9 полей, которые затребуют своеобразного разбора xml с условиями, конвертацией даты и прочими плюшками. акцент внимания на то, что вставить в запись надо не буквенный код аэропорта, а его id-шник из справочника

//======================================================

Mysql:
CREATE TABLE `flights` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `from` smallint(6) unsigned default '0',
  `to` smallint(6) unsigned default '0',
  `back` tinyint(1) unsigned default '0',
  `start` date default NULL,
  `stop` date default NULL,
  `adult` tinyint(1) unsigned default '0',
  `child` tinyint(1) unsigned default '0',
  `infant` tinyint(1) unsigned default '0',
  `price` decimal(12, 2) default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

CREATE TABLE `airports` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `code` varchar(3) default '',
  `name` varchar(64) default '',
  `contry` smallint(6) unsigned default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8


Console:\n
cd project folder\n
php -S localhost:8001\n
\n
Browser:\n
http://localhost:8001?ACTION=GET_FLIGHT_FROM_XML