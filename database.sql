-- Users table
CREATE TABLE tblUsers (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    googleID VARCHAR(255) DEFAULT NULL,
    name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Auctions table
CREATE TABLE tblAuctions (
    auctionID INT AUTO_INCREMENT PRIMARY KEY,
    itemName VARCHAR(255) NOT NULL,
    description TEXT,
    startingBid DECIMAL(10,2) NOT NULL,
    currentBid DECIMAL(10,2) DEFAULT NULL,
    highestBidderID INT DEFAULT NULL,
    endTime DATETIME NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (highestBidderID) REFERENCES tblUsers(userID)
);

-- Purchases table
CREATE TABLE tblAankopen (
    aankoopID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    ticketType VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    totalPrice DECIMAL(10,2) NOT NULL,
    purchaseDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    visitDate DATE NOT NULL,
    FOREIGN KEY (userID) REFERENCES tblUsers(userID)
);