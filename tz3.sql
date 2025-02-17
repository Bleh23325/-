-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 16 2025 г., 20:04
-- Версия сервера: 8.2.0
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tz3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `address`
--

CREATE TABLE `address` (
  `id_adres` int NOT NULL,
  `street` varchar(30) NOT NULL,
  `house` int NOT NULL,
  `Worker` int NOT NULL,
  `city` varchar(50) NOT NULL,
  `apartment` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `address`
--

INSERT INTO `address` (`id_adres`, `street`, `house`, `Worker`, `city`, `apartment`) VALUES
(6, 'Григорьевская', 2, 6, 'Ярославль', 2),
(7, 'Прощина', 3, 8, 'Нижний новгород', 3),
(8, 'Гаврилова', 1, 9, 'Ульяновск', 2),
(9, 'Опорина', 3, 11, 'Набережные челны', 1),
(10, 'Колотушкина', 2, 12, 'Новгород', 2),
(15, 'Улица', 2, 23, 'Тутаев', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `data_worker`
--

CREATE TABLE `data_worker` (
  `id_dw` int NOT NULL,
  `seria_pasporta` int NOT NULL,
  `nomer_pasporta` int NOT NULL,
  `Worker` int NOT NULL,
  `who_issue` varchar(60) NOT NULL,
  `when_issue` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `data_worker`
--

INSERT INTO `data_worker` (`id_dw`, `seria_pasporta`, `nomer_pasporta`, `Worker`, `who_issue`, `when_issue`) VALUES
(6, 5151, 214124, 6, 'УМВД', '2025-02-01'),
(7, 1241, 452345, 8, 'УМВД', '2025-02-06'),
(8, 1133, 414141, 9, 'УМВД', '2025-02-04'),
(9, 1235, 764317, 11, 'УМВД', '2025-02-02'),
(10, 1223, 125151, 12, 'УМВД', '2025-02-07'),
(15, 1241, 123512, 23, 'УМВД', '2020-02-13');

-- --------------------------------------------------------

--
-- Структура таблицы `department`
--

CREATE TABLE `department` (
  `id_departament` int NOT NULL,
  `department` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `department`
--

INSERT INTO `department` (`id_departament`, `department`) VALUES
(1, '3В'),
(2, '2Е'),
(3, 'Системный'),
(4, 'Управленческий'),
(5, '6Г');

-- --------------------------------------------------------

--
-- Структура таблицы `Dismissed`
--

CREATE TABLE `Dismissed` (
  `id_dis` int NOT NULL,
  `dismissed` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Dismissed`
--

INSERT INTO `Dismissed` (`id_dis`, `dismissed`) VALUES
(1, 'Уволен'),
(2, 'Работает'),
(3, 'В отпуске'),
(4, 'В декрете'),
(5, 'На больничном');

-- --------------------------------------------------------

--
-- Структура таблицы `info_worker`
--

CREATE TABLE `info_worker` (
  `id_iw` int NOT NULL,
  `phone` varchar(30) NOT NULL,
  `Worker` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `info_worker`
--

INSERT INTO `info_worker` (`id_iw`, `phone`, `Worker`) VALUES
(7, '+7 (213) 523-15-21', 6),
(8, '+7 (512) 421-42-44', 8),
(9, '+7 (112) 241-41-41', 9),
(10, '+7 (658) 585-68-56', 11),
(11, '+7 (898) 797-97-97', 12),
(18, '+7 (121) 251-23-55', 23);

-- --------------------------------------------------------

--
-- Структура таблицы `Job_title`
--

CREATE TABLE `Job_title` (
  `id_jt` int NOT NULL,
  `Job_title` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Job_title`
--

INSERT INTO `Job_title` (`id_jt`, `Job_title`) VALUES
(1, 'Системный администратор'),
(2, 'Бухгалтер'),
(3, 'HR'),
(4, 'Менеджер по продажам'),
(5, 'Уборщик');

-- --------------------------------------------------------

--
-- Структура таблицы `roots`
--

CREATE TABLE `roots` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `roots`
--

INSERT INTO `roots` (`id`, `name`) VALUES
(1, 'Админ'),
(2, 'Менеджер');

-- --------------------------------------------------------

--
-- Структура таблицы `time_of_absence`
--

CREATE TABLE `time_of_absence` (
  `id` int NOT NULL,
  `fst_date` date NOT NULL,
  `last_date` date DEFAULT NULL,
  `worker` int NOT NULL,
  `statys` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `time_of_absence`
--

INSERT INTO `time_of_absence` (`id`, `fst_date`, `last_date`, `worker`, `statys`) VALUES
(1, '2025-01-01', NULL, 11, 2),
(2, '2024-12-01', '2025-01-07', 8, 4),
(3, '2012-01-25', '2019-01-25', 9, 3),
(5, '2025-02-01', NULL, 6, 2),
(6, '2025-02-01', '2025-02-14', 8, 4),
(7, '2025-02-01', '2025-02-10', 9, 5),
(8, '2025-02-14', NULL, 12, 1),
(9, '2025-02-09', NULL, 23, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `roots` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `roots`) VALUES
(1, 'admin', 'admin', 1),
(2, 'user', 'user', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `Worker`
--

CREATE TABLE `Worker` (
  `id_w` int NOT NULL,
  `Familia` varchar(30) NOT NULL,
  `Ima` varchar(30) NOT NULL,
  `Otchestvo` varchar(30) NOT NULL,
  `department` int NOT NULL,
  `jod_title` int NOT NULL,
  `data_rojdenia` date NOT NULL,
  `zarplata` int NOT NULL,
  `data_zachislenia` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Worker`
--

INSERT INTO `Worker` (`id_w`, `Familia`, `Ima`, `Otchestvo`, `department`, `jod_title`, `data_rojdenia`, `zarplata`, `data_zachislenia`) VALUES
(6, 'Григорьев', 'Дмитрий', 'Григорьевич', 2, 1, '2024-10-05', 1231, '2024-10-03'),
(8, 'Олегов', 'Дмитрий', 'Олегович', 4, 4, '2024-10-03', 55, '2024-10-05'),
(9, 'Анатольев', 'Анаьолий', 'Анатольевич', 3, 4, '2024-10-02', 41414, '2024-10-04'),
(11, 'Агапов', 'Олег', 'Агапович', 1, 5, '1990-10-01', 25000, '2024-08-01'),
(12, 'Борисов', 'Денис', 'Владимирович', 1, 1, '2025-02-01', 12000, '2025-02-02'),
(23, 'Бутузов', 'Иван', 'Александрович', 1, 1, '2006-03-14', 67000, '2025-02-09');

--
-- Триггеры `Worker`
--
DELIMITER $$
CREATE TRIGGER `after_worker_insert` AFTER INSERT ON `Worker` FOR EACH ROW BEGIN
    -- Вставка новой записи в таблицу time_of_absence с использованием данных из вставленного работника
    INSERT INTO time_of_absence (fst_date, worker, statys)
    VALUES (NEW.data_zachislenia, NEW.id_w, 2);
END
$$
DELIMITER ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id_adres`),
  ADD KEY `Worker` (`Worker`);

--
-- Индексы таблицы `data_worker`
--
ALTER TABLE `data_worker`
  ADD PRIMARY KEY (`id_dw`),
  ADD KEY `id_dw` (`id_dw`),
  ADD KEY `Worker` (`Worker`);

--
-- Индексы таблицы `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id_departament`);

--
-- Индексы таблицы `Dismissed`
--
ALTER TABLE `Dismissed`
  ADD PRIMARY KEY (`id_dis`);

--
-- Индексы таблицы `info_worker`
--
ALTER TABLE `info_worker`
  ADD PRIMARY KEY (`id_iw`),
  ADD KEY `Worker` (`Worker`);

--
-- Индексы таблицы `Job_title`
--
ALTER TABLE `Job_title`
  ADD PRIMARY KEY (`id_jt`);

--
-- Индексы таблицы `roots`
--
ALTER TABLE `roots`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `time_of_absence`
--
ALTER TABLE `time_of_absence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `worker` (`worker`),
  ADD KEY `statys` (`statys`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roots` (`roots`);

--
-- Индексы таблицы `Worker`
--
ALTER TABLE `Worker`
  ADD PRIMARY KEY (`id_w`),
  ADD KEY `telefon` (`department`,`jod_title`),
  ADD KEY `department` (`department`),
  ADD KEY `jod_title` (`jod_title`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `address`
--
ALTER TABLE `address`
  MODIFY `id_adres` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `data_worker`
--
ALTER TABLE `data_worker`
  MODIFY `id_dw` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `department`
--
ALTER TABLE `department`
  MODIFY `id_departament` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `Dismissed`
--
ALTER TABLE `Dismissed`
  MODIFY `id_dis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `info_worker`
--
ALTER TABLE `info_worker`
  MODIFY `id_iw` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `Job_title`
--
ALTER TABLE `Job_title`
  MODIFY `id_jt` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `time_of_absence`
--
ALTER TABLE `time_of_absence`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `Worker`
--
ALTER TABLE `Worker`
  MODIFY `id_w` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`Worker`) REFERENCES `Worker` (`id_w`);

--
-- Ограничения внешнего ключа таблицы `data_worker`
--
ALTER TABLE `data_worker`
  ADD CONSTRAINT `data_worker_ibfk_1` FOREIGN KEY (`Worker`) REFERENCES `Worker` (`id_w`);

--
-- Ограничения внешнего ключа таблицы `info_worker`
--
ALTER TABLE `info_worker`
  ADD CONSTRAINT `info_worker_ibfk_1` FOREIGN KEY (`Worker`) REFERENCES `Worker` (`id_w`);

--
-- Ограничения внешнего ключа таблицы `time_of_absence`
--
ALTER TABLE `time_of_absence`
  ADD CONSTRAINT `time_of_absence_ibfk_1` FOREIGN KEY (`worker`) REFERENCES `Worker` (`id_w`),
  ADD CONSTRAINT `time_of_absence_ibfk_2` FOREIGN KEY (`statys`) REFERENCES `Dismissed` (`id_dis`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roots`) REFERENCES `roots` (`id`);

--
-- Ограничения внешнего ключа таблицы `Worker`
--
ALTER TABLE `Worker`
  ADD CONSTRAINT `worker_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department` (`id_departament`),
  ADD CONSTRAINT `worker_ibfk_5` FOREIGN KEY (`jod_title`) REFERENCES `Job_title` (`id_jt`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
