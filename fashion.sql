-- Smart Fashion SQL Database Setup
-- ---------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Create database
CREATE DATABASE IF NOT EXISTS `fashion` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fashion`;

-- --------------------------------------------------------
-- Table: collection_table
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `collection_table` (
  `c_id` INT AUTO_INCREMENT PRIMARY KEY,
  `collection` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `collection_table` (`c_id`, `collection`) VALUES
(1, 'women'),
(2, 'men'),
(3, 'accessories')
ON DUPLICATE KEY UPDATE `collection` = VALUES(`collection`);

-- --------------------------------------------------------
-- Table: products
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `pId` INT(11) NOT NULL AUTO_INCREMENT,
  `pName` VARCHAR(255) NOT NULL,
  `pPrice` DECIMAL(10,2) NOT NULL,
  `pQty` INT(11) NOT NULL DEFAULT 0,
  `pCollection` VARCHAR(255) NOT NULL,
  `pImg` VARCHAR(500) NOT NULL,
  `pAImg` VARCHAR(500) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`pId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert products (existing data maintained)
INSERT INTO `products` 
(`pId`, `pName`, `pPrice`, `pQty`, `pCollection`, `pImg`, `pAImg`) 
VALUES
(1, 'pleated skirt', 4.00, 2, 'women', 'https://static.wixstatic.com/media/84770f_92ad288f7eb849c68652826216de56dc.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_92ad288f7eb849c68652826216de56dc.webp', 'https://static.wixstatic.com/media/84770f_8660c9c0527c4107869f4159281b3957.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_8660c9c0527c4107869f4159281b3957.webp'),
(2, 'Navy Suit', 14.00, 0, 'men', 'https://static.wixstatic.com/media/84770f_174a1833c8394bd1af133b4795f7e33b.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_174a1833c8394bd1af133b4795f7e33b.webp', 'https://static.wixstatic.com/media/84770f_aa82a8fc608f41ac82139564e5d10e93.jpg/v1/fill/w_625,h_625,al_c,q_85,usm_0.66_1.00_0.01/84770f_aa82a8fc608f41ac82139564e5d10e93.webp'),
(3, 'printed chiffon dress', 20.00, 2, 'women', 'https://static.wixstatic.com/media/84770f_8745e3f04d8e4662ba23b4d575602616.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_8745e3f04d8e4662ba23b4d575602616.webp', 'https://static.wixstatic.com/media/84770f_6d22d3fd17e74bd2948588ab408fd231.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_6d22d3fd17e74bd2948588ab408fd231.webp'),
(4, 'printed top', 19.00, 2, 'women', 'https://static.wixstatic.com/media/84770f_fd449a34dc724242b659cf1476e073dd.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_fd449a34dc724242b659cf1476e073dd.webp', 'https://static.wixstatic.com/media/84770f_c088c666b370405194b8dd2d12f248b3.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_c088c666b370405194b8dd2d12f248b3.webp'),
(5, 'Striped Mini Skirt', 18.00, 2, 'women', 'https://static.wixstatic.com/media/84770f_c4238cadc128447a90c5c4ec07cb8532.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_c4238cadc128447a90c5c4ec07cb8532.webp', 'https://static.wixstatic.com/media/84770f_315c3ab348104bcd83146532d72e2ef1.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_315c3ab348104bcd83146532d72e2ef1.webp'),
(6, 'Knee Length Dress', 13.00, 2, 'women', 'https://static.wixstatic.com/media/84770f_d9b51e08b51c43319cc6a36f808d7bf9.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_d9b51e08b51c43319cc6a36f808d7bf9.webp', 'https://static.wixstatic.com/media/84770f_0f5fb1bcf1ff442396a8cde48380693a.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_0f5fb1bcf1ff442396a8cde48380693a.webp'),
(7, 'Plaint White Shorts', 7.00, 2, 'women', 'https://static.wixstatic.com/media/84770f_8bba0cf04db947de8d9542f081e545ad.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_8bba0cf04db947de8d9542f081e545ad.webp', 'https://static.wixstatic.com/media/84770f_a93eaf951ab94858b7a2976d9489744e.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_a93eaf951ab94858b7a2976d9489744e.webp'),
(8, 'Chinese Shirt', 26.00, 0, 'women', 'https://static.wixstatic.com/media/84770f_685a7e84e1f6459592ea52b10d6ec70f.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_685a7e84e1f6459592ea52b10d6ec70f.webp', 'https://static.wixstatic.com/media/84770f_bdb4bcdcc06d4dcf94ad9181c12557be.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_bdb4bcdcc06d4dcf94ad9181c12557be.webp'),
(9, 'Straight Pant', 19.00, 0, 'women', 'https://static.wixstatic.com/media/84770f_2c647846e52d4b5d9cc3a6e1e8e38ba5.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_2c647846e52d4b5d9cc3a6e1e8e38ba5.webp', 'https://static.wixstatic.com/media/84770f_7fa958c8c2414423a3e585c5c27779b3.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_7fa958c8c2414423a3e585c5c27779b3.webp'),
(10, 'casual t-shirt', 18.00, 0, 'men', 'https://static.wixstatic.com/media/84770f_fa2cb753b49b43faa1e9b3da2e8f72c0.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_fa2cb753b49b43faa1e9b3da2e8f72c0.webp', 'https://static.wixstatic.com/media/84770f_b26b140c7de74e6a910e3ad516980681.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_b26b140c7de74e6a910e3ad516980681.webp'),
(11, 'Side Zip T-Shirt', 22.00, 0, 'men', 'https://static.wixstatic.com/media/84770f_ad69de0ded0845ecb2be4c325fc0d7b2.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_ad69de0ded0845ecb2be4c325fc0d7b2.webp', 'https://static.wixstatic.com/media/84770f_f9de83a5802c4c8fb60703dd1f706df4.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_f9de83a5802c4c8fb60703dd1f706df4.webp'),
(12, 'Pattern t-shirt', 13.00, 0, 'men', 'https://static.wixstatic.com/media/84770f_1720a41d8a7f4f51be2e910b346afb2d.jpg/v1/fill/w_625,h_625,al_c,q_85,usm_0.66_1.00_0.01/84770f_1720a41d8a7f4f51be2e910b346afb2d.webp', 'https://static.wixstatic.com/media/84770f_b4141d6d0e294322abc6e3906f6d5056.jpg/v1/fill/w_625,h_625,al_c,q_85,usm_0.66_1.00_0.01/84770f_b4141d6d0e294322abc6e3906f6d5056.webp'),
(13, 'Foldover Purse', 6.00, 0, 'accessories', 'https://static.wixstatic.com/media/84770f_86f6b8eadd0144ff8123a42842ae37a5.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_86f6b8eadd0144ff8123a42842ae37a5.webp', 'https://static.wixstatic.com/media/84770f_04708dcecb9446e89167e6ac4ca7ba82.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_04708dcecb9446e89167e6ac4ca7ba82.webp'),
(14, 'Handcraft Clutch', 5.00, 2, 'accessories', 'https://static.wixstatic.com/media/84770f_5a144aac1232446497775d71890dd188.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_5a144aac1232446497775d71890dd188.webp', 'https://static.wixstatic.com/media/84770f_775e9afa293b43f89eec9736b8c42d08.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_775e9afa293b43f89eec9736b8c42d08.webp'),
(15, 'Green Necklace', 2.00, 2, 'accessories', 'https://static.wixstatic.com/media/84770f_f8e77c4203dd40c19eb913ae3b4ccb1a.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_f8e77c4203dd40c19eb913ae3b4ccb1a.webp', 'https://static.wixstatic.com/media/84770f_dc75910eb7ab4aa782542c6af9770fd2.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_dc75910eb7ab4aa782542c6af9770fd2.webp'),
(16, 'black necklace', 12.00, 2, 'accessories', 'https://static.wixstatic.com/media/84770f_480159a712a64e04addd28ca090a5cb8.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_480159a712a64e04addd28ca090a5cb8.webp', 'https://static.wixstatic.com/media/84770f_dad2829a0b8148a78096aecefb5d84e9.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_dad2829a0b8148a78096aecefb5d84e9.webp'),
(17, 'Silver Ornament', 11.00, 0, 'accessories', 'https://static.wixstatic.com/media/84770f_e38be8301e254aeab5e032ecba6be649.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_e38be8301e254aeab5e032ecba6be649.webp', 'https://static.wixstatic.com/media/84770f_0c74978c768a4103b2cbc34f488ffd6d.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_0c74978c768a4103b2cbc34f488ffd6d.webp'),
(18, 'Grey belt', 29.00, 0, 'accessories', 'https://static.wixstatic.com/media/84770f_21d996d935eb4aa186fcf2431893cb5f.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_21d996d935eb4aa186fcf2431893cb5f.webp', 'https://static.wixstatic.com/media/84770f_7e554aae23ec4e3b8f0d5ed0603f2916.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_7e554aae23ec4e3b8f0d5ed0603f2916.webp'),
(19, 'Cateye Sunglasses', 49.00, 0, 'accessories', 'https://static.wixstatic.com/media/84770f_054a710970294c8a91eeea7d1b20f44d.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_054a710970294c8a91eeea7d1b20f44d.webp', 'https://static.wixstatic.com/media/84770f_119268614ecb4043b06bd1893ea3eb02.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_119268614ecb4043b06bd1893ea3eb02.webp'),
(20, 'Round sunglasses', 32.00, 0, 'accessories', 'https://static.wixstatic.com/media/84770f_3d58b9767c674b0f9c75805fe1e63a85.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_3d58b9767c674b0f9c75805fe1e63a85.webp', 'https://static.wixstatic.com/media/84770f_43f5bbf6c55b4e5a96386072e3f469b5.png/v1/fill/w_625,h_625,al_c,q_90,usm_0.66_1.00_0.01/84770f_43f5bbf6c55b4e5a96386072e3f469b5.webp');

-- --------------------------------------------------------
-- Table: registration (user info)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `registration` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `profile` VARCHAR(255) DEFAULT '/img/default-profile.png',
  `number` VARCHAR(20) DEFAULT '',
  `address` TEXT DEFAULT '',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert admin and sample users
INSERT INTO `registration` (`id`, `username`, `email`, `password`, `profile`, `number`, `address`) VALUES
(1, 'abc', 'admin@admin.com', '$2y$10$H1NcHr5i5tTMfIVzyc7v4OWIDsQ29xsU809NVz8zc0CZKxtTbR9fK', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtVwUoQz0A0BFEsRVq4gLh2KMy4l8RCY8ExP9cXDg4xgr1z1u3RmqLRvNLB-DMPNIuIeM', '', ''),
(2, 'kemi', 'kemi2@kem2i.com', '$2y$10$.ItW3zWp.IFGCRxEr.j6muR/n3pH3shTxe6HCNL1ZNKhnQR5UJJUi', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtVwUoQz0A0BFEsRVq4gLh2KMy4l8RCY8ExP9cXDg4xgr1z1u3RmqLRvNLB-DMPNIuIeM', '', ''),
(3, 'channa', 'channa@gmail.com', '$2y$10$eV.EwS3lX6AySv2ytz9peOLd7nkySaxKa2QGbkYVAhEDzahVOCM9.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtVwUoQz0A0BFEsRVq4gLh2KMy4l8RCY8ExP9cXDg4xgr1z1u3RmqLRvNLB-DMPNIuIeM', '9876543210', '123,somewhere,something'),
(4, 'user', 'user@gmail.com', '$2y$10$grCluFz7RBLiI1eLwsvtsO7pG7oxIvf8NOsq/BNp9USYGaVQ3erva', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtVwUoQz0A0BFEsRVq4gLh2KMy4l8RCY8ExP9cXDg4xgr1z1u3RmqLRvNLB-DMPNIuIeM', '', ''),
(5, 'hash', 'hash@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtVwUoQz0A0BFEsRVq4gLh2KMy4l8RCY8ExP9cXDg4xgr1z1u3RmqLRvNLB-DMPNIuIeM', '9874561230', 'somewhere'),
(6, 'John', 'John789@gmail.com', '$2y$10$dzGeyBKkkdmL8HZp.9dIle7q7Ffe5QXy0utqwn1LaG0rJewBWtrY6', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtVwUoQz0A0BFEsRVq4gLh2KMy4l8RCY8ExP9cXDg4xgr1z1u3RmqLRvNLB-DMPNIuIeM', '', ''),
(7, 'testuser', 'test@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/img/default-profile.png', '', '');

-- --------------------------------------------------------
-- Table: cart
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart` (
  `cId` INT AUTO_INCREMENT PRIMARY KEY,
  `pId` INT NOT NULL,
  `uName` VARCHAR(255) NOT NULL,
  `pQty` INT NOT NULL DEFAULT 1,
  FOREIGN KEY (`pId`) REFERENCES `products` (`pId`) ON DELETE CASCADE,
  FOREIGN KEY (`uName`) REFERENCES `registration` (`username`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert some example cart data
INSERT INTO `cart` (`cId`, `pId`, `uName`, `pQty`) VALUES
(1, 1, 'channa', 1),
(10, 17, 'channa', 1),
(14, 11, 'channa', 2),
(17, 7, 'user', 4),
(18, 14, 'hash', 1);

COMMIT;