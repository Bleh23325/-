-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 11 2024 г., 14:07
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tz2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `address`
--

CREATE TABLE `address` (
  `id_adres` int NOT NULL,
  `street` varchar(30) NOT NULL,
  `house` int NOT NULL,
  `Worker` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `address`
--

INSERT INTO `address` (`id_adres`, `street`, `house`, `Worker`) VALUES
(6, 'Григорьев', 2, 6),
(7, 'Прощина', 3, 8),
(8, 'Гаврилова', 1, 9),
(9, 'Опорина', 3, 11);

-- --------------------------------------------------------

--
-- Структура таблицы `data_worker`
--

CREATE TABLE `data_worker` (
  `id_dw` int NOT NULL,
  `seria_pasporta` int NOT NULL,
  `nomer_pasporta` int NOT NULL,
  `Worker` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `data_worker`
--

INSERT INTO `data_worker` (`id_dw`, `seria_pasporta`, `nomer_pasporta`, `Worker`) VALUES
(6, 5151, 214124, 6),
(7, 1241, 452345, 8),
(8, 1133, 414141, 9),
(9, 1235, 764317, 11);

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
(2, 'Не уволен'),
(3, 'В отпуске'),
(4, 'В дикрете'),
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
(10, '+7 (658) 585-68-56', 11);

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
  `data_zachislenia` date NOT NULL,
  `dismissed` int NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Worker`
--

INSERT INTO `Worker` (`id_w`, `Familia`, `Ima`, `Otchestvo`, `department`, `jod_title`, `data_rojdenia`, `zarplata`, `data_zachislenia`, `dismissed`) VALUES
(6, 'Григорьев', 'Григорий', 'Григорьевич', 4, 1, '2024-10-05', 1231, '2024-10-03', 2),
(8, 'Олегов', 'Олег', 'Олегович', 1, 4, '2024-10-03', 55, '2024-10-05', 1),
(9, 'Анатольев', 'Анаьолий', 'Анатольевич', 3, 4, '2024-10-02', 41414, '2024-10-04', 2),
(11, 'Агапов', 'Агап', 'Агапович', 1, 5, '1990-10-01', 25000, '2024-08-01', 5);

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
-- Индексы таблицы `Worker`
--
ALTER TABLE `Worker`
  ADD PRIMARY KEY (`id_w`),
  ADD KEY `telefon` (`department`,`jod_title`),
  ADD KEY `department` (`department`),
  ADD KEY `jod_title` (`jod_title`),
  ADD KEY `Dismissed` (`dismissed`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `address`
--
ALTER TABLE `address`
  MODIFY `id_adres` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `data_worker`
--
ALTER TABLE `data_worker`
  MODIFY `id_dw` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `department`
--
ALTER TABLE `department`
  MODIFY `id_departament` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Dismissed`
--
ALTER TABLE `Dismissed`
  MODIFY `id_dis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `info_worker`
--
ALTER TABLE `info_worker`
  MODIFY `id_iw` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `Job_title`
--
ALTER TABLE `Job_title`
  MODIFY `id_jt` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `Worker`
--
ALTER TABLE `Worker`
  MODIFY `id_w` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- Ограничения внешнего ключа таблицы `Worker`
--
ALTER TABLE `Worker`
  ADD CONSTRAINT `worker_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department` (`id_departament`),
  ADD CONSTRAINT `worker_ibfk_5` FOREIGN KEY (`jod_title`) REFERENCES `Job_title` (`id_jt`),
  ADD CONSTRAINT `worker_ibfk_6` FOREIGN KEY (`dismissed`) REFERENCES `Dismissed` (`id_dis`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
