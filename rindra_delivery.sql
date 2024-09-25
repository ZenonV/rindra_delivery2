-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Сен 25 2024 г., 04:06
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `rindra_delivery`
--

-- --------------------------------------------------------

--
-- Структура таблицы `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `driver_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `delivery_status` tinyint(1) DEFAULT 0,
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `drivers`
--

INSERT INTO `drivers` (`id`, `product_name`, `driver_name`, `quantity`, `delivery_status`, `order_id`) VALUES
(1, 'Car', 'Driver1', 2, 0, 0),
(2, 'Car', 'Driver1', 2, 0, 0),
(3, 'Car2', 'Driver1', 1, 0, 0),
(4, 'car3', 'Driver1', 4, 0, 0),
(5, 'car4', 'Driver1', 1, 0, 0),
(6, 'car5', 'Driver1', 1, 0, 0),
(7, 'Car 7', 'Driver1', 1, 0, 0),
(8, 'car8', 'Driver1', 3, 0, 0),
(9, 'Car', 'Driver1', 2, 0, 0),
(10, 'Car', 'Driver1', 2, 0, 0),
(11, 'Car', 'Driver1', 2, 0, 0),
(12, 'Car', 'Driver1', 2, 0, 0),
(13, 'Car', 'Driver1', 2, 0, 0),
(14, 'Car', 'Driver1', 2, 0, 0),
(15, 'Car', 'Driver2', 2, 0, 0),
(16, 'Car', 'Driver2', 2, 0, 0),
(17, 'Car', 'Driver1', 2, 0, 0),
(18, 'Car', 'Driver1', 2, 0, 0),
(19, 'car8', 'Driver1', 3, 0, 0),
(20, 'car8', 'Driver1', 3, 0, 0),
(42, 'car3', 'Driver1', 4, 0, 3),
(43, 'car3', 'Driver1', 4, 0, 3),
(44, 'car3', 'Driver1', 4, 0, 3),
(45, 'car3', 'Driver1', 4, 0, 3),
(46, 'Car', 'Driver1', 2, 0, 1),
(51, 'Car 7', 'Driver1', 1, 0, 6),
(52, 'Car 7', 'Driver1', 1, 0, 6),
(53, 'Car 7', 'Driver1', 1, 0, 6),
(54, 'car5', 'Driver1', 1, 0, 5),
(55, 'Car 7', 'Driver1', 1, 0, 6),
(66, 'Car2', 'Driver1', 1, 0, 2),
(67, 'Car2', 'Driver1', 1, 0, 2),
(68, 'Car2', 'Driver1', 1, 0, 2),
(69, 'car4', 'Driver1', 1, 0, 4),
(70, 'car4', 'Driver1', 1, 0, 4),
(71, 'car8', 'Driver1', 3, 0, 7),
(72, 'Car 8', 'Driver1', 3, 0, 8),
(73, 'Car 8', 'Driver1', 3, 0, 8),
(74, 'Car79', 'Driver1', 4, 0, 9),
(75, 'Car79', 'Driver1', 4, 0, 9),
(76, 'car10', 'Driver1', 8, 0, 10),
(77, 'car10', 'Driver1', 8, 0, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL,
  `order_status` varchar(20) DEFAULT 'pending',
  `delivered` tinyint(1) DEFAULT 0,
  `user_confirmation` varchar(50) DEFAULT 'Awaiting Confirmation',
  `driver_assigned` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `username`, `product_name`, `order_date`, `quantity`, `order_status`, `delivered`, `user_confirmation`, `driver_assigned`) VALUES
(1, 'Daniil', 'Car', '2024-09-24 18:48:08', 2, 'Assigned', 1, 'Awaiting Confirmation', 0),
(2, 'Daniil', 'Car2', '2024-09-24 18:54:44', 1, 'Assigned', 1, 'Awaiting Confirmation', 1),
(3, 'Daniil', 'car3', '2024-09-24 18:54:50', 4, 'Assigned', 1, 'Awaiting Confirmation', 1),
(4, 'Daniil', 'car4', '2024-09-24 19:00:03', 1, 'pending', 1, 'Awaiting Confirmation', 1),
(5, 'Daniil', 'car5', '2024-09-24 19:55:32', 1, 'Delivery Confirmed', 1, 'Awaiting Confirmation', 0),
(6, 'Daniil', 'Car 7', '2024-09-24 20:17:06', 1, 'Delivery Confirmed', 1, 'Awaiting Confirmation', 0),
(7, 'Daniil', 'car8', '2024-09-24 20:26:43', 3, 'Delivery Confirmed', 1, 'Confirmed', 1),
(8, 'admin', 'Car 8', '2024-09-24 21:08:20', 3, 'Delivered', 1, 'Awaiting Confirmation', 1),
(9, 'Daniil', 'Car79', '2024-09-24 21:59:39', 4, 'Delivery Confirmed', 1, 'Confirmed', 1),
(10, 'Daniil', 'car10', '2024-09-24 22:02:53', 8, 'Delivered', 1, 'Awaiting Confirmation', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `status`) VALUES
(1, 'Daniil', 'daniilrezepin@gmail.com', '$2y$10$Jii4a/ixhbXbEGEA8NHAY.5T4JQm2NuMssgZwJRDP0t1vMktyZKjG', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
