DROP TABLE IF EXISTS profiles;
DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS profiles (
  user_id INT PRIMARY KEY,
  telefono VARCHAR(20),
  nazionalita VARCHAR(50),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (username, password) VALUES
('admin', 'admin123'),
('alessio.rossi', 'AleRo2023!'),
('martina.bianchi', 'Marti#789'),
('federico.verdi', 'FedeVerdi88'),
('giulia.conti', 'GiuC@nti22'),
('andrea.moretto', 'Andre_Mor99'),
('chiara.fontana', 'ChFont123'),
('marco.galli', 'MGalli$456'),
('elena.riva', 'Elen4Riva!'),
('simone.ferri', 'Simo_F123'),
('valentina.pace', 'Val_Pace2022'),
('davide.greco', 'D@vGreco33'),
('ilaria.moro', 'IllyM89_'),
('riccardo.pini', 'RickPini!22'),
('francesca.villa', 'FranVilla#1'),
('luca.costa', 'LucaC_777'),
('serena.mattioli', 'SereMat!94'),
('giovanni.lodi', 'GioLodi88'),
('marta.neri', 'MaNeri_543'),
('giorgio.riva', 'GiorRiv2021'),
('sofia.cantoni', 'SofCan#99'),
('paolo.bianchi', 'PBianch!234');

INSERT INTO profiles (user_id, telefono, nazionalita) VALUES
(1, '3200000001', 'Italia'),
(2, '3200000002', 'Italia'),
(3, '3200000003', 'Francia'),
(4, '3200000004', 'Germania'),
(5, '3200000005', 'Spagna'),
(6, '3200000006', 'Italia'),
(7, '3200000007', 'Italia'),
(8, '3200000008', 'Italia'),
(9, '3200000009', 'Italia'),
(10, '3200000010', 'Italia'),
(11, '3200000011', 'Italia'),
(12, '3200000012', 'Italia'),
(13, '3200000013', 'Italia'),
(14, '3200000014', 'Italia'),
(15, '3200000015', 'Italia'),
(16, '3200000016', 'Italia'),
(17, '3200000017', 'Italia'),
(18, '3200000018', 'Italia'),
(19, '3200000019', 'Italia'),
(20, '3200000020', 'Italia'),
(21, '3200000021', 'Italia');
