CREATE DATABASE IF NOT EXISTS PetManagementDB;
USE PetManagementDB;

CREATE TABLE IF NOT EXISTS Pets (
    PetID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Age INT,
    Street VARCHAR(100),
    City VARCHAR(50),
    ZipCode VARCHAR(10),
    State CHAR(2),
    TypeofPet VARCHAR(30)
);

CREATE TABLE IF NOT EXISTS Owners (
    OID INT AUTO_INCREMENT PRIMARY KEY,
    LastName VARCHAR(50) NOT NULL,
    Street VARCHAR(100),
    City VARCHAR(50),
    ZipCode VARCHAR(10),
    State CHAR(2),
    Age INT,
    AnnualIncome DECIMAL(12, 2)
);

CREATE TABLE IF NOT EXISTS Owns (
    PetID INT,
    Year YEAR,
    OID INT,
    PetAgeatOwnership INT,
    PricePaid DECIMAL(10, 2),
    PRIMARY KEY (PetID, Year, OID),
    FOREIGN KEY (PetID) REFERENCES Pets(PetID),
    FOREIGN KEY (OID) REFERENCES Owners(OID)
);

CREATE TABLE IF NOT EXISTS Likes (
    PetID INT,
    TypeofFood VARCHAR(30),
    PRIMARY KEY (PetID, TypeofFood),
    FOREIGN KEY (PetID) REFERENCES Pets(PetID)
);

CREATE TABLE IF NOT EXISTS Foods (
    FoodID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Brand VARCHAR(50),
    TypeofFood VARCHAR(30),
    Price DECIMAL(8, 2),
    ItemWeight DECIMAL(10, 3),
    ClassofFood VARCHAR(30)
);

CREATE TABLE IF NOT EXISTS Purchases (
    OID INT,
    FoodID INT,
    PetID INT,
    Month VARCHAR(20),
    Year INT,
    Quantity INT,
    PRIMARY KEY (OID, FoodID, PetID, Month, Year),
    FOREIGN KEY (OID) REFERENCES Owners(OID),
    FOREIGN KEY (FoodID) REFERENCES Foods(FoodID),
    FOREIGN KEY (PetID) REFERENCES Pets(PetID)
);

/*
Create a Pets database with the following tables:
Pets (PetID, Name, Age, Street, City, ZipCode, State, TypeofPet)
Owners (OID, LastName, Street, City, ZipCode, State, Age, AnnualIncome)
Owns (PetID, Year, OID, PetAgeatOwnership, PricePaid)
Likes (PetID, TypeofFood)
Foods (FoodID, Name, Brand, TypeofFood, Price, ItemWeight, ClassofFood)
Purchases (OID, FoodID, PetID, Month, Year, Quantity)
*/