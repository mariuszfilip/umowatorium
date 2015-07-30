-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `formularze_ewidencja`
--

CREATE TABLE IF NOT EXISTS `formularze_ewidencja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazwa` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `czy_token` tinyint(1) NOT NULL DEFAULT '1',
  `czy_obieg_dokumentow` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `formularze_pola`
--

CREATE TABLE IF NOT EXISTS `formularze_pola` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_formularza` int(11) DEFAULT NULL,
  `typ_pola` int(1) DEFAULT NULL,
  `id_nadrzednego` int(11) DEFAULT '0',
  `pozycjonowanie` int(11) DEFAULT NULL,
  `etykieta` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `nazwa_pola` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `wartosc_domyslna` text COLLATE utf8_polish_ci,
  `szerokosc_pola` int(11) DEFAULT NULL,
  `wysokosc_pola` int(11) NOT NULL DEFAULT '0',
  `szerokosc_etykiety` int(11) DEFAULT NULL,
  `margin_lewy` int(11) DEFAULT NULL,
  `margin_gora` int(11) DEFAULT NULL,
  `class_css` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `ilosc_kolumn` int(11) DEFAULT '0',
  `czy_wymagane` int(11) DEFAULT NULL,
  `walidacja` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `id_slownika` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `nazwa_formularza` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `walidacja_serwer` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `nazwa` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `on_click` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `podpowiedz` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `autostartupload` tinyint(4) NOT NULL DEFAULT '0',
  `offset_lewy` int(11) NOT NULL DEFAULT '0',
  `offset_gora` int(11) NOT NULL DEFAULT '0',
  `readonly` int(11) NOT NULL DEFAULT '0',
  `maska` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `formularze_pola_typy`
--

CREATE TABLE IF NOT EXISTS `formularze_pola_typy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazwa` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `opis` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=23 ;

--
-- Zrzut danych tabeli `formularze_pola_typy`
--

INSERT INTO `formularze_pola_typy` (`id`, `nazwa`, `opis`, `status`) VALUES
(1, 'Input', '', 1),
(2, 'Select', '', 1),
(3, 'Calendar', '', 1),
(4, 'Textarea', '', 1),
(5, 'Input Readonly', '', 1),
(6, 'Button', '', 1),
(7, 'Checkbox', '', 1),
(8, 'Hidden', '', 1),
(9, 'File', '', 1),
(10, 'Template', '', 1),
(11, 'Upload', '', 1),
(12, 'Blok', '', 1),
(13, 'Kolumn', '', 1),
(14, 'Radio', '', 1),
(15, 'Label', '', 1),
(16, 'Container', '', 1),
(17, 'Uploader', '', 1),
(18, 'Fieldset', '', 1),
(19, 'Password', '', 1),
(20, 'Editor', '', 1),
(21, 'Combo', '', 1),
(22, 'Ckeditor', 'dociągnąc na koncu plik dhtmlx/ckeditor.js', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `formularze_texty_walidacji`
--

CREATE TABLE IF NOT EXISTS `formularze_texty_walidacji` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=16 ;

--
-- Zrzut danych tabeli `formularze_texty_walidacji`
--

INSERT INTO `formularze_texty_walidacji` (`id`, `text`) VALUES
(1, '[1] wpisz pole'),
(2, '[2] błędne nazwisko'),
(3, '[3] błędny pesel'),
(4, '[4] błędna kwota'),
(5, '[5] błędny format dowodu'),
(6, '[6] błędny foramt kodu pocztowego'),
(7, '[7] wybierz jedną z pozycji'),
(8, '[8] błędny nr Telefonu'),
(9, '[9] błędne imie'),
(10, '[10] błędny telefon'),
(11, '[11] błędny NIP'),
(12, '[12] błędny regon'),
(13, '[13] nieprawidłowy dzień'),
(14, '[14] Nieprawidłowa liczba dzieci'),
(15, '[15] błędny email');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy_ewidencja`
--

CREATE TABLE IF NOT EXISTS `uzytkownicy_ewidencja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imie` varchar(64) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
  `nazwisko` varchar(64) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
  `login` varchar(20) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
  `haslo` varchar(32) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
  `data_dodania` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_2` (`login`),
  KEY `nazwisko` (`nazwisko`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `uzytkownicy_ewidencja`
--

INSERT INTO `uzytkownicy_ewidencja` (`id`, `imie`, `nazwisko`, `login`, `haslo`, `data_dodania`, `status`) VALUES
(1, 'Mariusz', 'Filipkowski', 'mario', '4297f44b13955235245b2497399d7a93', '2014-08-18 00:00:00', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
