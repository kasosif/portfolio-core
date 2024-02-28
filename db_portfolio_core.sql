-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 28 fév. 2024 à 10:46
-- Version du serveur : 8.0.34
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_portfolio_core`
--

-- --------------------------------------------------------

--
-- Structure de la table `activities`
--

CREATE TABLE `activities` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `draft` tinyint NOT NULL DEFAULT '0',
  `candidate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `activities`
--

INSERT INTO `activities` (`id`, `title`, `description`, `draft`, `candidate_id`, `created_at`, `updated_at`) VALUES
(5, 'Hosting', 'I have the skills to host applications on remote servers', 0, 1, '2024-01-23 13:48:54', '2024-01-23 13:51:19');

-- --------------------------------------------------------

--
-- Structure de la table `candidates`
--

CREATE TABLE `candidates` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` longtext COLLATE utf8mb4_unicode_ci,
  `date_of_birth` date DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci,
  `activated` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidates`
--

INSERT INTO `candidates` (`id`, `first_name`, `last_name`, `email`, `job_description`, `about`, `date_of_birth`, `phone_number`, `address`, `activated`, `created_at`, `updated_at`) VALUES
(1, 'Khalil', 'Fakhfekh', 'kasosif@gmail.com', 'Fullstack Web Developer', 'I\'m ambitious and ready to innovate, both in my private and professional life. Developing my career, broadening my knowledge and honing my skills have always been my priorities.', '1994-12-14', '+2165540911', 'Olympic City, Tunis', 1, '2024-01-10 14:59:37', '2024-01-18 14:18:01');

-- --------------------------------------------------------

--
-- Structure de la table `candidate_language`
--

CREATE TABLE `candidate_language` (
  `candidate_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidate_language`
--

INSERT INTO `candidate_language` (`candidate_id`, `language_id`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `candidate_skill`
--

CREATE TABLE `candidate_skill` (
  `candidate_id` bigint UNSIGNED NOT NULL,
  `skill_id` bigint UNSIGNED NOT NULL,
  `percentage` int DEFAULT NULL,
  `icon_only` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidate_skill`
--

INSERT INTO `candidate_skill` (`candidate_id`, `skill_id`, `percentage`, `icon_only`) VALUES
(1, 1, 85, 0),
(1, 4, 60, 0),
(1, 6, 90, 0),
(1, 7, NULL, 1),
(1, 8, 60, 0),
(1, 9, NULL, 1),
(1, 10, 80, 0),
(1, 11, NULL, 1),
(1, 12, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issuer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `draft` tinyint NOT NULL DEFAULT '0',
  `candidate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `certificates`
--

INSERT INTO `certificates` (`id`, `date`, `title`, `number`, `issuer`, `draft`, `candidate_id`, `created_at`, `updated_at`) VALUES
(3, '2019-02-18', 'Develop web applications with Angular', '6139966200', 'OpenClassRooms', 0, 1, '2024-01-25 11:08:59', '2024-01-25 11:08:59');

-- --------------------------------------------------------

--
-- Structure de la table `contact_requests`
--

CREATE TABLE `contact_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `candidate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `curriculum_vitaes`
--

CREATE TABLE `curriculum_vitaes` (
  `id` bigint UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public` int NOT NULL DEFAULT '1',
  `language_id` bigint UNSIGNED NOT NULL,
  `candidate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `curriculum_vitaes`
--

INSERT INTO `curriculum_vitaes` (`id`, `path`, `name`, `public`, `language_id`, `candidate_id`, `created_at`, `updated_at`) VALUES
(10, 'resumes/1/en/khalil_fakhfekh_resume.pdf', 'khalil_fakhfekh_resume.pdf', 1, 1, 1, '2024-02-26 11:14:27', '2024-02-26 11:14:27'),
(11, 'resumes/1/fr/khalil_fakhfekh_resume.pdf', 'khalil_fakhfekh_resume.pdf', 1, 2, 1, '2024-02-26 15:18:57', '2024-02-26 15:18:57');

-- --------------------------------------------------------

--
-- Structure de la table `education`
--

CREATE TABLE `education` (
  `id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  `degree` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acknowledgement` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institute` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `institute_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `draft` tinyint NOT NULL DEFAULT '0',
  `candidate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `education`
--

INSERT INTO `education` (`id`, `start_date`, `end_date`, `current`, `degree`, `acknowledgement`, `institute`, `institute_country`, `draft`, `candidate_id`, `created_at`, `updated_at`) VALUES
(2, '2016-02-01', '2019-01-01', 0, 'National diploma in Computer Engineering', 'With Honors', 'ESPRIT', 'Tunisia', 0, 1, '2024-01-25 11:02:55', '2024-01-25 11:02:55'),
(3, '2013-01-01', '2016-02-01', 0, 'Licentiate in Business Computing', 'With Honors', 'IHEC Carthage', 'Tunisia', 0, 1, '2024-02-24 15:03:38', '2024-02-24 15:03:38'),
(4, '2013-01-01', '2013-01-01', 0, 'Bachelor of Computer Sciences', 'With Honors', 'Menzah 6 High school', 'Tunisia', 0, 1, '2024-02-24 15:04:23', '2024-02-24 15:04:23');

-- --------------------------------------------------------

--
-- Structure de la table `experiences`
--

CREATE TABLE `experiences` (
  `id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '0',
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `draft` tinyint NOT NULL DEFAULT '0',
  `candidate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `experiences`
--

INSERT INTO `experiences` (`id`, `start_date`, `end_date`, `current`, `company_name`, `company_country`, `title`, `description`, `draft`, `candidate_id`, `created_at`, `updated_at`) VALUES
(3, '2018-04-01', '2018-08-01', 0, 'Octopus+', 'Tunisia', 'Web Developer Intern', 'My first internship helped me to demonstrate my skills in the professional world.', 0, 1, '2024-02-24 15:15:15', '2024-02-24 15:15:15'),
(4, '2019-02-01', '2019-06-01', 0, 'TuninfoForYou', 'Tunisia', 'FullStack Web Developer Intern', 'End-of-studies project for national diploma in computer engineering', 0, 1, '2024-02-24 15:20:21', '2024-02-24 15:20:21'),
(5, '2019-12-01', '2021-02-01', 0, 'Webradar', 'Tunisia', 'FullStack Web Developer', 'blablabla', 0, 1, '2024-02-24 15:22:14', '2024-02-24 15:22:14'),
(6, '2021-03-01', '2022-03-01', 0, 'Kabylis', 'Tunisia', 'Co-founder & FullStack Web Developer', 'blablabla', 0, 1, '2024-02-24 15:27:23', '2024-02-24 15:27:23'),
(7, '2022-06-01', NULL, 1, 'Diool', 'Tunisia', 'FullStack Web Developer', 'blablabla', 0, 1, '2024-02-24 15:29:33', '2024-02-24 15:29:33');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English', '2024-01-15 09:03:07', '2024-01-15 09:03:51'),
(2, 'fr', 'Français', '2024-01-10 15:44:12', NULL),
(3, 'ar', 'العربية', '2024-01-10 16:14:58', NULL),
(5, 'es', 'Espagnol', '2024-01-15 09:04:32', '2024-01-15 09:04:32');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_10_103632_create_candidates_table', 1),
(6, '2024_01_10_103700_create_languages_table', 1),
(7, '2024_01_10_103730_create_social_accounts_table', 1),
(8, '2024_01_10_103749_create_curriculum_vitaes_table', 1),
(9, '2024_01_10_103818_create_activities_table', 1),
(10, '2024_01_10_103848_create_testimonies_table', 1),
(11, '2024_01_10_104016_create_education_table', 1),
(12, '2024_01_10_104032_create_experiences_table', 1),
(13, '2024_01_10_104046_create_certificates_table', 1),
(14, '2024_01_10_104105_create_skills_table', 1),
(15, '2024_01_10_104115_create_projects_table', 1),
(16, '2024_01_10_104138_create_contact_requests_table', 1),
(17, '2024_01_10_111522_create_pictures_table', 1),
(18, '2024_01_10_111757_create_translations_table', 1),
(19, '2024_01_10_145617_create_candidate_skill_table', 2),
(20, '2024_01_11_161155_create_candidate_language_table', 3),
(21, '2014_10_12_000000_create_users_table', 4),
(23, '2024_01_16_103922_create_taches_table', 5);

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pictures`
--

CREATE TABLE `pictures` (
  `id` bigint UNSIGNED NOT NULL,
  `galleriable_id` bigint UNSIGNED NOT NULL,
  `galleriable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `main` tinyint(1) NOT NULL,
  `public` tinyint NOT NULL DEFAULT '0',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pictures`
--

INSERT INTO `pictures` (`id`, `galleriable_id`, `galleriable_type`, `main`, `public`, `path`, `name`, `created_at`, `updated_at`) VALUES
(7, 1, 'App\\Models\\Candidate', 1, 1, 'pictures/candidates/1/XxpCKEhqDRCw.png', 'XxpCKEhqDRCw.png', '2024-01-17 13:26:31', '2024-01-18 13:37:50'),
(10, 4, 'App\\Models\\Activity', 1, 1, 'pictures/activities/4/9KDPyYffBOsa.png', '9KDPyYffBOsa.png', '2024-01-21 12:49:53', '2024-01-21 12:49:53'),
(11, 2, 'App\\Models\\Testimony', 1, 1, 'pictures/testimonies/2/pp8RWm4BIZqE.jpg', 'pp8RWm4BIZqE.jpg', '2024-01-25 10:50:39', '2024-01-25 10:50:39'),
(12, 3, 'App\\Models\\Certificate', 1, 1, 'pictures/certificates/3/Z7UDTwxURuFn.png', 'Z7UDTwxURuFn.png', '2024-01-25 11:10:18', '2024-01-25 11:10:18'),
(15, 1, 'App\\Models\\Language', 1, 1, 'pictures/languages/1/4ucDN2ymXuY1.svg', '4ucDN2ymXuY1.svg', '2024-01-25 14:16:19', '2024-01-25 14:16:19'),
(16, 2, 'App\\Models\\Language', 1, 1, 'pictures/languages/2/MOxAOMFW1jc3.webp', 'MOxAOMFW1jc3.webp', '2024-01-25 14:17:40', '2024-01-25 14:17:40'),
(17, 3, 'App\\Models\\Language', 1, 1, 'pictures/languages/3/2wtB6md49vK0.webp', '2wtB6md49vK0.webp', '2024-01-25 14:18:42', '2024-01-25 14:18:42'),
(19, 2, 'App\\Models\\Project', 1, 1, 'pictures/projects/2/xTEby8u2NG7D.png', 'xTEby8u2NG7D.png', '2024-02-23 09:06:32', '2024-02-23 09:06:32'),
(21, 3, 'App\\Models\\Project', 1, 1, 'pictures/projects/3/okTe9wxAi4ef.png', 'okTe9wxAi4ef.png', '2024-02-24 15:37:29', '2024-02-24 15:37:29'),
(22, 4, 'App\\Models\\Project', 1, 1, 'pictures/projects/4/RpWC1RCojYZL.png', 'RpWC1RCojYZL.png', '2024-02-24 15:40:42', '2024-02-24 15:40:42'),
(23, 5, 'App\\Models\\Project', 1, 1, 'pictures/projects/5/qCq2hTklIKvB.png', 'qCq2hTklIKvB.png', '2024-02-24 15:43:53', '2024-02-24 15:43:53'),
(24, 6, 'App\\Models\\Project', 1, 1, 'pictures/projects/6/vnBPcRuGXLNR.png', 'vnBPcRuGXLNR.png', '2024-02-24 15:45:32', '2024-02-24 15:45:32'),
(25, 3, 'App\\Models\\Testimony', 1, 1, 'pictures/testimonies/3/oaVcRmGQlJIV.jpg', 'oaVcRmGQlJIV.jpg', '2024-02-24 15:50:08', '2024-02-24 15:50:08'),
(27, 4, 'App\\Models\\Skill', 1, 1, 'pictures/skills/4/He5luYfTSMVE.png', 'He5luYfTSMVE.png', '2024-02-26 13:16:13', '2024-02-26 13:16:13'),
(28, 6, 'App\\Models\\Skill', 1, 1, 'pictures/skills/6/NTqyVWB7Mom1.png', 'NTqyVWB7Mom1.png', '2024-02-26 13:17:34', '2024-02-26 13:17:34'),
(30, 10, 'App\\Models\\Skill', 1, 1, 'pictures/skills/10/R9y82poeIaA3.png', 'R9y82poeIaA3.png', '2024-02-26 13:24:56', '2024-02-26 13:24:56'),
(34, 7, 'App\\Models\\Skill', 1, 1, 'pictures/skills/7/mnaxjqdYkBWy.svg', 'mnaxjqdYkBWy.svg', '2024-02-26 14:03:52', '2024-02-26 14:03:52'),
(35, 9, 'App\\Models\\Skill', 1, 1, 'pictures/skills/9/1As2OArubtXq.png', '1As2OArubtXq.png', '2024-02-26 14:08:34', '2024-02-26 14:08:35'),
(36, 11, 'App\\Models\\Skill', 1, 1, 'pictures/skills/11/xtDeroSliZIa.png', 'xtDeroSliZIa.png', '2024-02-26 14:09:15', '2024-02-26 14:09:15'),
(37, 3, 'App\\Models\\SocialAccount', 1, 1, 'pictures/social_accounts/3/EUx4CBOrzoB2.png', 'EUx4CBOrzoB2.png', '2024-02-26 14:11:27', '2024-02-26 14:11:27'),
(38, 4, 'App\\Models\\SocialAccount', 1, 1, 'pictures/social_accounts/4/8uQekuFkXusH.png', '8uQekuFkXusH.png', '2024-02-26 14:12:01', '2024-02-26 14:12:01'),
(42, 5, 'App\\Models\\SocialAccount', 1, 1, 'pictures/social_accounts/5/Q5qWM0TyXzoL.png', 'Q5qWM0TyXzoL.png', '2024-02-26 14:24:32', '2024-02-26 14:24:32'),
(43, 1, 'App\\Models\\Skill', 1, 1, 'pictures/skills/1/3YdlGtoDfimb.png', '3YdlGtoDfimb.png', '2024-02-26 14:40:40', '2024-02-26 14:40:40'),
(44, 8, 'App\\Models\\Skill', 1, 1, 'pictures/skills/8/GaBohaKW6NMp.png', 'GaBohaKW6NMp.png', '2024-02-26 14:41:30', '2024-02-26 14:41:30'),
(45, 12, 'App\\Models\\Skill', 1, 1, 'pictures/skills/12/Rk7df8I3Up06.png', 'Rk7df8I3Up06.png', '2024-02-26 14:51:49', '2024-02-26 14:51:49');

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `draft` tinyint NOT NULL DEFAULT '0',
  `candidate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`id`, `date`, `description`, `title`, `link`, `draft`, `candidate_id`, `created_at`, `updated_at`) VALUES
(2, '2019-01-01', 'A rental platform for guest houses and experiences', 'Kabylis Project', 'https://kabylis.tn', 0, 1, '2024-01-25 12:44:17', '2024-01-25 12:44:17'),
(3, '2012-06-06', 'Diool, the digital platform adopted by African companies to centralize their transactions and accelerate payments', 'Diool App', 'https://diool.com', 0, 1, '2024-02-24 15:36:52', '2024-02-24 15:36:52'),
(4, '2020-11-01', 'Inovatis, a website for a training center with the possibility to apply online', 'Inovatis Website', 'https://inovatis.tn', 0, 1, '2024-02-24 15:40:09', '2024-02-24 15:40:09'),
(5, '2019-06-01', 'WeLearn App for teachers and students with an E-learning module', 'WeLearn App', NULL, 0, 1, '2024-02-24 15:43:20', '2024-02-24 15:43:20'),
(6, '2020-02-01', 'Informational platform on startups and visa programs around the world', 'Swibo App', 'https://swibo.org', 0, 1, '2024-02-24 15:45:20', '2024-02-24 15:45:20');

-- --------------------------------------------------------

--
-- Structure de la table `skills`
--

CREATE TABLE `skills` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `skills`
--

INSERT INTO `skills` (`id`, `type`, `name`, `created_at`, `updated_at`) VALUES
(1, 'frontEnd', 'Angular', '2024-02-26 14:57:19', '2024-02-26 14:57:22'),
(4, 'frontEnd', 'React', '2024-02-24 14:37:35', '2024-02-24 14:37:35'),
(5, 'frontEnd', 'VueJs', '2024-02-24 14:37:42', '2024-02-24 14:37:42'),
(6, 'backEnd', 'Laravel', '2024-02-24 14:37:56', '2024-02-24 14:37:56'),
(7, 'backEnd', 'PHP', '2024-02-24 14:38:03', '2024-02-24 14:38:03'),
(8, 'backEnd', 'NodeJs', '2024-02-24 14:38:11', '2024-02-24 14:38:11'),
(9, 'backEnd', 'Java', '2024-02-24 14:38:16', '2024-02-24 14:38:16'),
(10, 'backEnd', 'Spring', '2024-02-24 14:38:25', '2024-02-24 14:38:25'),
(11, 'frontEnd', 'HTML', '2024-02-24 14:38:25', '2024-02-24 14:38:25'),
(12, 'frontEnd', 'CSS', '2024-02-24 14:38:25', '2024-02-24 14:38:25');

-- --------------------------------------------------------

--
-- Structure de la table `social_accounts`
--

CREATE TABLE `social_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `candidate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `social_accounts`
--

INSERT INTO `social_accounts` (`id`, `type`, `name`, `link`, `candidate_id`, `created_at`, `updated_at`) VALUES
(3, 'LinkedIn', 'Khalil Fakhfekh', 'https://www.linkedin.com/in/khalil-fakhfekh-1478b7159/', 1, '2024-01-25 13:19:21', '2024-01-25 13:19:21'),
(4, 'Facebook', 'Khalil Fakhfekh', 'https://www.facebook.com/kasosif', 1, '2024-02-24 14:33:13', '2024-02-24 14:33:13'),
(5, 'WhatsApp', 'Khalil Fakhfekh', 'https://wa.me/21655740911', 1, '2024-02-26 14:20:37', '2024-02-26 14:20:37');

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE `taches` (
  `id` bigint UNSIGNED NOT NULL,
  `taskable_id` bigint UNSIGNED NOT NULL,
  `taskable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `taches`
--

INSERT INTO `taches` (`id`, `taskable_id`, `taskable_type`, `description`, `created_at`, `updated_at`) VALUES
(2, 2, 'App\\Models\\Experience', 'Social media data collection using Facebook Graph API', '2024-01-16 09:52:18', '2024-01-16 09:52:18'),
(3, 2, 'App\\Models\\Experience', 'Generation of detailed weekly reports for brands', '2024-01-16 09:52:32', '2024-01-16 09:52:32'),
(5, 2, 'App\\Models\\Experience', 'Integration of website mockups', '2024-01-16 09:52:56', '2024-01-16 09:52:56'),
(8, 3, 'App\\Models\\Experience', 'Development of an e-commerce platform for agri-food products', '2024-02-24 15:15:42', '2024-02-24 15:15:42'),
(9, 3, 'App\\Models\\Experience', 'Development of a consulting platform', '2024-02-24 15:16:12', '2024-02-24 15:16:12'),
(10, 4, 'App\\Models\\Experience', 'Development of a web-based school management application with an E-learning module', '2024-02-24 15:21:00', '2024-02-24 15:21:00'),
(11, 5, 'App\\Models\\Experience', 'Collecting data from social networks using the Facebook Graph API', '2024-02-24 15:22:47', '2024-02-24 15:22:47'),
(12, 5, 'App\\Models\\Experience', 'Generate detailed weekly reports for brands', '2024-02-24 15:23:07', '2024-02-24 15:23:07'),
(13, 5, 'App\\Models\\Experience', 'Integration of website mock-ups', '2024-02-24 15:23:24', '2024-02-24 15:23:24'),
(14, 6, 'App\\Models\\Experience', 'Development of a guest house rental and experience platform', '2024-02-24 15:27:43', '2024-02-24 15:27:43'),
(15, 6, 'App\\Models\\Experience', 'Implementation of secure webservices (REST APIs)', '2024-02-24 15:28:01', '2024-02-24 15:28:01'),
(16, 6, 'App\\Models\\Experience', 'Trainee supervision', '2024-02-24 15:28:19', '2024-02-24 15:28:19'),
(17, 6, 'App\\Models\\Experience', 'Platform deployment and maintenance on AWS', '2024-02-24 15:28:35', '2024-02-24 15:28:35'),
(18, 7, 'App\\Models\\Experience', 'Integrating Figma models into Angular components and consuming webservices', '2024-02-24 15:29:49', '2024-02-24 15:29:49'),
(19, 7, 'App\\Models\\Experience', 'Exporting REST webservices and generating permission-based tokens', '2024-02-24 15:30:38', '2024-02-24 15:30:38'),
(20, 7, 'App\\Models\\Experience', 'Implementation and debugging of Java code for the Diool core project', '2024-02-24 15:30:52', '2024-02-24 15:30:52');

-- --------------------------------------------------------

--
-- Structure de la table `testimonies`
--

CREATE TABLE `testimonies` (
  `id` bigint UNSIGNED NOT NULL,
  `testimony` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `testimony_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `testimony_job_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `testimony_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `draft` tinyint NOT NULL DEFAULT '0',
  `candidate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `testimonies`
--

INSERT INTO `testimonies` (`id`, `testimony`, `testimony_name`, `testimony_job_description`, `testimony_country`, `draft`, `candidate_id`, `created_at`, `updated_at`) VALUES
(2, 'Khalil was able to adapt quickly in a new environment like ours in order to be fully independent on the job. He invested himself with a great professionalism in all the missions entrusted to him.', 'Oussama Romdhane', 'CEO at Kabylis', 'Tunisia', 0, 1, '2024-01-25 10:49:32', '2024-02-24 15:48:24'),
(3, 'Khalil is an engineer that likes challenges and invests in new projects.', 'Anouar Khemeja', 'CTO at Webradar', 'Tunisia', 0, 1, '2024-02-24 15:49:27', '2024-02-24 15:49:27');

-- --------------------------------------------------------

--
-- Structure de la table `translations`
--

CREATE TABLE `translations` (
  `id` bigint UNSIGNED NOT NULL,
  `translatable_id` bigint UNSIGNED NOT NULL,
  `translatable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimony` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimony_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimony_job_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimony_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `degree` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acknowledgement` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institute` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institute_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issuer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `translations`
--

INSERT INTO `translations` (`id`, `translatable_id`, `translatable_type`, `first_name`, `last_name`, `job_description`, `about`, `address`, `title`, `description`, `testimony`, `testimony_name`, `testimony_job_description`, `testimony_country`, `degree`, `acknowledgement`, `institute`, `institute_country`, `company_name`, `company_country`, `issuer`, `name`, `language_id`, `created_at`, `updated_at`) VALUES
(3, 1, 'App\\Models\\Activity', NULL, NULL, NULL, NULL, NULL, 'Integración de maquetas de sitios web', 'Utilizo tecnologías web para transformar maquetas en sitios web', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, '2024-01-16 13:37:41', '2024-01-16 13:37:41'),
(5, 2, 'App\\Models\\Project', NULL, NULL, NULL, NULL, NULL, 'Projet Kabylis', 'Une plateforme de location de maisons d\'hôtes et d\'expériences', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2024-02-13 09:32:38', '2024-02-13 09:32:38'),
(6, 1, 'App\\Models\\Candidate', 'Khalil', 'Fakhfekh', 'Développeur Fullstack Web', 'Je suis ambitieux et prêt à innover, tant dans ma vie privée que professionnelle. Développer ma carrière, élargir mes connaissances et perfectionner mes compétences ont toujours été mes priorités.', 'Cité olympique, Tunis', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2024-02-13 09:39:13', '2024-02-13 09:39:13'),
(7, 2, 'App\\Models\\Education', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Diplôme national d\'ingénieur en informatique', 'Mention bien', 'ESPRIT', 'Tunisie', NULL, NULL, NULL, NULL, 2, '2024-02-28 09:13:21', '2024-02-28 09:13:21'),
(8, 3, 'App\\Models\\Education', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Licence Appliquée en informatique de gestion', 'Mention bien', 'IHEC Carthage', 'Tunisie', NULL, NULL, NULL, NULL, 2, '2024-02-28 09:14:09', '2024-02-28 09:14:09'),
(9, 4, 'App\\Models\\Education', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Baccalauréat en Informatique', 'Mention bien', 'Lycée Menzah 6', 'Tunisie', NULL, NULL, NULL, NULL, 2, '2024-02-28 09:15:17', '2024-02-28 09:15:17'),
(10, 3, 'App\\Models\\Experience', NULL, NULL, NULL, NULL, NULL, 'Stagiaire développeur web', 'Mon premier stage qui m\'a aidé a mettre en evidence mes competences dans le monde professionnel', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Octopus+', 'Tunisie', NULL, NULL, 2, '2024-02-28 09:20:32', '2024-02-28 09:20:32'),
(11, 4, 'App\\Models\\Experience', NULL, NULL, NULL, NULL, NULL, 'Stagiaire développeur web FullStack', 'Projet de fin d\'études pour le diplôme national d\'ingénieur en informatique', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TuninfoForYou', 'Tunisie', NULL, NULL, 2, '2024-02-28 09:22:30', '2024-02-28 09:22:30'),
(12, 5, 'App\\Models\\Experience', NULL, NULL, NULL, NULL, NULL, 'Développeur web FullStack', 'blablabla', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Webradar', 'Tunisie', NULL, NULL, 2, '2024-02-28 09:25:42', '2024-02-28 09:25:42'),
(13, 6, 'App\\Models\\Experience', NULL, NULL, NULL, NULL, NULL, 'Co-fondateur et développeur web FullStack', 'Mise en place d\'une plateforme de location de maison d\'hôtes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kabylis', 'Tunisie', NULL, NULL, 2, '2024-02-28 09:28:25', '2024-02-28 09:28:25'),
(14, 7, 'App\\Models\\Experience', NULL, NULL, NULL, NULL, NULL, 'Développeur web FullStack', 'blablalba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Diool', 'Tunisie, Cameroun', NULL, NULL, 2, '2024-02-28 09:30:51', '2024-02-28 09:30:51'),
(15, 3, 'App\\Models\\Certificate', NULL, NULL, NULL, NULL, NULL, 'Développer des applications web avec Angular', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OpenClassRooms', NULL, 2, '2024-02-28 09:32:39', '2024-02-28 09:32:39'),
(16, 3, 'App\\Models\\Project', NULL, NULL, NULL, NULL, NULL, 'Plateforme Diool', 'Diool, la plateforme numérique adoptée par les entreprises africaines pour centraliser leurs transactions et accélérer les paiements', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2024-02-28 09:34:44', '2024-02-28 09:34:44'),
(17, 4, 'App\\Models\\Project', NULL, NULL, NULL, NULL, NULL, 'Site web Inovatis', 'Inovatis, un site web pour un centre de formation avec la possibilité de postuler en ligne', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2024-02-28 09:35:31', '2024-02-28 09:35:31'),
(18, 5, 'App\\Models\\Project', NULL, NULL, NULL, NULL, NULL, 'Application WeLearn', 'WeLearn est une application Angular destinée pour les enseignants et les étudiants avec un module d\'apprentissage en ligne', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2024-02-28 09:37:19', '2024-02-28 09:37:19'),
(19, 6, 'App\\Models\\Project', NULL, NULL, NULL, NULL, NULL, 'Swibo', 'Plateforme d\'information sur les start-ups et les programmes de visas dans le monde entier', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2024-02-28 09:38:03', '2024-02-28 09:38:03'),
(20, 2, 'App\\Models\\Testimony', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Khalil a su s\'adapter rapidement à un nouvel environnement comme le nôtre afin d\'être totalement autonome sur le terrain. Il s\'est investi avec un grand professionnalisme dans toutes les missions qui lui ont été confiées.', 'Oussama Romdhane', 'PDG de Kabylis', 'Tunisie', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2024-02-28 09:40:30', '2024-02-28 09:40:30'),
(21, 3, 'App\\Models\\Testimony', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Khalil est un ingénieur qui aime les défis et s\'investit dans de nouveaux projets.', 'Anouar Khemeja', 'Directeur technique chez Webradar', 'Tunisie', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2024-02-28 09:41:43', '2024-02-28 09:41:43');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CANDIDATE',
  `candidate_id` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `candidate_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Khalil Fakhfekh', 'kasosif@gmail.com', '2024-01-11 17:09:16', '$2y$10$4zzj4gRlhbOhPoBwMO.lGeUK4T6QceUKyVWJqWEE0IP12CjEsL3GK', 'ADMIN', 1, NULL, '2024-01-11 17:09:16', '2024-01-11 17:09:16');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_candidate_id_index` (`candidate_id`);

--
-- Index pour la table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `candidates_email_unique` (`email`);

--
-- Index pour la table `candidate_language`
--
ALTER TABLE `candidate_language`
  ADD PRIMARY KEY (`candidate_id`,`language_id`),
  ADD KEY `candidate_language_candidate_id_index` (`candidate_id`),
  ADD KEY `candidate_language_language_id_index` (`language_id`);

--
-- Index pour la table `candidate_skill`
--
ALTER TABLE `candidate_skill`
  ADD PRIMARY KEY (`candidate_id`,`skill_id`),
  ADD KEY `candidate_skill_candidate_id_index` (`candidate_id`),
  ADD KEY `candidate_skill_skill_id_index` (`skill_id`);

--
-- Index pour la table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificates_candidate_id_index` (`candidate_id`);

--
-- Index pour la table `contact_requests`
--
ALTER TABLE `contact_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_requests_candidate_id_index` (`candidate_id`);

--
-- Index pour la table `curriculum_vitaes`
--
ALTER TABLE `curriculum_vitaes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curriculum_vitaes_language_id_index` (`language_id`),
  ADD KEY `curriculum_vitaes_candidate_id_index` (`candidate_id`);

--
-- Index pour la table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `education_candidate_id_index` (`candidate_id`);

--
-- Index pour la table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `experiences_candidate_id_index` (`candidate_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_code_unique` (`code`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_candidate_id_index` (`candidate_id`);

--
-- Index pour la table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `social_accounts_candidate_id_index` (`candidate_id`);

--
-- Index pour la table `taches`
--
ALTER TABLE `taches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `testimonies`
--
ALTER TABLE `testimonies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `testimonies_candidate_id_index` (`candidate_id`);

--
-- Index pour la table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translations_language_id_index` (`language_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_candidate_id_index` (`candidate_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `contact_requests`
--
ALTER TABLE `contact_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `curriculum_vitaes`
--
ALTER TABLE `curriculum_vitaes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `education`
--
ALTER TABLE `education`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `social_accounts`
--
ALTER TABLE `social_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `taches`
--
ALTER TABLE `taches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `testimonies`
--
ALTER TABLE `testimonies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `candidate_language`
--
ALTER TABLE `candidate_language`
  ADD CONSTRAINT `candidate_language_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidate_language_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `candidate_skill`
--
ALTER TABLE `candidate_skill`
  ADD CONSTRAINT `candidate_skill_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidate_skill_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contact_requests`
--
ALTER TABLE `contact_requests`
  ADD CONSTRAINT `contact_requests_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `curriculum_vitaes`
--
ALTER TABLE `curriculum_vitaes`
  ADD CONSTRAINT `curriculum_vitaes_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curriculum_vitaes_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `education`
--
ALTER TABLE `education`
  ADD CONSTRAINT `education_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `experiences`
--
ALTER TABLE `experiences`
  ADD CONSTRAINT `experiences_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD CONSTRAINT `social_accounts_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `testimonies`
--
ALTER TABLE `testimonies`
  ADD CONSTRAINT `testimonies_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
