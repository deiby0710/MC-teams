-- Crear tabla team
CREATE TABLE IF NOT EXISTS team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    league VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    founded_year SMALLINT NOT NULL
);

-- Crear tabla player
CREATE TABLE IF NOT EXISTS player (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    dorsal INT NOT NULL,
    position VARCHAR(50) NOT NULL,
    team_id INT NOT NULL,
    FOREIGN KEY (team_id) REFERENCES team(id)
);