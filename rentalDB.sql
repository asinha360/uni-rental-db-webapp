drop database if exists rentalDB;
create database if not exists rentalDB;
use rentalDB;

create table person (
	id char(5) not null,
    first_name varchar(60) not null,
    last_name varchar(60) not null,
    phone_num varchar(10) not null,
	primary key (id)
);

create table rental_manager (
    person_id char(5) not null,
    hire_date date not null,
    salary float not null check (salary >= 0.0),
    primary key (person_id),
    foreign key (person_id) references person(id) on delete cascade
);

create table rental_group (
	id char(4) not null,
	rental_type enum('House', 'Apartment', 'Room'),
	is_accessible boolean default FALSE,
    has_parking boolean default FALSE,
    num_bedrooms int,
    num_bathrooms int,
    rent_price float check (rent_price >= 0.0),
    date_listed date not null,
    laundry_type enum ('ensuite', 'shared') not null,
    primary key (id)
);

create table renter (
	person_id char(5) not null,
	rental_group_id char(4) not null,
	grad_year date not null,
	program varchar(255) not null,
	student_id char(7) not null,
	primary key (person_id),
	foreign key (person_id) references person(id) on delete cascade,
    foreign key (rental_group_id) references rental_group(id)
);

create table rental (
    id int not null auto_increment,
	rental_type enum ("House", "Apartment", "Room"),
    apt_num int,
    street varchar(255) not null,
    city varchar(255) not null,
    province char(2) not null,
    pc char(7) not null,
	num_bedrooms int not null,
    num_bathrooms int not null,
    laundry_type enum ('ensuite', 'shared'),
    has_parking boolean not null,
    is_accessible boolean default FALSE,
    rent float not null,
    date_listed date not null,
	primary key (id)
);

create table house (
	rental_id int not null,
	house_type enum("detached", "semi"),
	has_fenced_yard boolean not null,
	primary key (rental_id),
	foreign key (rental_id) references rental(id) on delete cascade
);

create table apartment (
	rental_id int not null,
	has_elevator boolean not null,
	floor int not null,
	primary key (rental_id),
	foreign key (rental_id) references rental(id) on delete cascade
);

create table room (
	rental_id int not null,
	roommates int not null,
	has_kitchen_privileges boolean not null,
	primary key (rental_id),
	foreign key (rental_id) references rental(id) on delete cascade
);

create table furnishings (
	room_rental_id int not null,
	furniture_name varchar(255) not null,
	quantity int not null,
	primary key (room_rental_id, furniture_name),
	foreign key (room_rental_id) references room(rental_id) on delete cascade
);

create table lease (
	rental_id int not null,
	rental_group_id char(4) not null,
	date_signed date not null,
	end_date date not null,
	rent_price float not null check (rent_price >= 0.0),
	primary key (rental_id, rental_group_id),
	foreign key (rental_id) references rental(id) on delete cascade,
	foreign key (rental_group_id) references rental_group(id) on delete cascade	
);

create table owns_rental (
	owner_id char(5) not null,
	rental_id int not null,
	primary key (owner_id, rental_id),
	foreign key (owner_id) references person(id) on delete cascade,
    foreign key (rental_id) references rental(id) on delete cascade
);

create table manages_rental (
    manager_id char(5) not null,
	date_started date not null,
    rental_id int not null,
    primary key (manager_id, rental_id),
    foreign key (manager_id) references rental_manager(person_id) on delete cascade,
    foreign key (rental_id) references rental(id) on delete cascade
);


-- Insert into person
insert into person (id, first_name, last_name, phone_num) values
('JO001', 'John', 'Doe', '1234567890'),
('JA002', 'Jane', 'Doe', '2345678901'),
('JI003', 'Jim', 'Beam', '3456789012'),
('JA004', 'Jack', 'Daniels', '4567890123'),
('JI005', 'Jill', 'Hill', '5678901234'),
('JO006', 'Joe', 'Bloggs', '6789012345'),
('JA007', 'Janet', 'Smith', '7890123456'),
('JA008', 'Jake', 'Snake', '8901234567'),
('JU009', 'Julia', 'Roberts', '9012345678'),
('JA010', 'Jason', 'Bourne', '0123456789'),
('RM001', 'Alex', 'Smith', '1123456789'),
('RO001', 'Morgan', 'Brown', '1223456789'),
('RM002', 'Jamie', 'Taylor', '1323456789'),
('RO002', 'Casey', 'Jones', '1423456789'),
('RM003', 'Jordan', 'Lee', '1523456789'),
('RO003', 'Taylor', 'Green', '1623456789'),
('RM004', 'Drew', 'White', '1723456789'),
('RO004', 'Blake', 'Black', '1823456789'),
('RM005', 'Charlie', 'Gray', '1923456789'),
('RO005', 'Sam', 'Stone', '2023456789'),
('RO006', 'John', 'Brown', '1223456789'),
('RO007', 'Luke', 'Taylor', '1323456787');


-- Insert into rental_manager
insert into rental_manager (person_id, hire_date, salary) values
('RM001', '2021-01-01', 50000.00),
('RM002', '2021-02-01', 60000.00),
('RM003', '2021-03-01', 70000.00),
('RM004', '2021-04-01', 80000.00),
('RM005', '2021-05-01', 90000.00);

-- Insert into rental_group
insert into rental_group (id, rental_type, is_accessible, has_parking, num_bedrooms, num_bathrooms, rent_price, date_listed, laundry_type) values
('0001', 'House', TRUE, TRUE, 3, 2, 1500.00, '2021-01-01', 'ensuite'),
('0002', 'Apartment', FALSE, TRUE, 2, 1, 1200.00, '2021-02-01', 'shared'),
('0003', 'Room', TRUE, FALSE, 1, 1, 800.00, '2021-03-01', 'ensuite'),
('0004', 'House', FALSE, FALSE, 4, 3, 2000.00, '2021-04-01', 'shared'),
('0005', 'Apartment', TRUE, TRUE, 2, 2, 1300.00, '2021-05-01', 'ensuite'),
('0006', 'Room', FALSE, TRUE, 3, 2, 1600.00, '2021-06-01', 'shared'),
('0007', 'House', TRUE, FALSE, 1, 1, 900.00, '2021-07-01', 'ensuite'),
('0008', 'Apartment', FALSE, FALSE, 4, 3, 2100.00, '2021-08-01', 'shared'),
('0009', 'Room', TRUE, TRUE, 3, 2, 1700.00, '2021-09-01', 'ensuite'),
('0010', 'House', FALSE, TRUE, 2, 1, 1100.00, '2021-10-01', 'shared');

-- Insert into renter
insert into renter (person_id, rental_group_id, grad_year, program, student_id) values
('JO001', '0001', '2024-05-01', 'Computer Science', 'CS2021A'),
('JA002', '0002', '2023-05-01', 'Business Administration', 'BA2021A'),
('JI003', '0003', '2022-05-01', 'Mechanical Engineering', 'ME2021B'),
('JA004', '0004', '2025-05-01', 'Psychology', 'PS2021A'),
('JI005', '0005', '2024-12-01', 'Biology', 'BI2021A'),
('JO006', '0006', '2023-12-01', 'Chemistry', 'CH2021B'),
('JA007', '0007', '2022-12-01', 'Physics', 'PH2021B'),
('JA008', '0008', '2025-12-01', 'Mathematics', 'MA2021B'),
('JU009', '0009', '2023-05-01', 'History', 'HI2021A'),
('JA010', '0010', '2024-05-01', 'Art', 'AR2021A');

-- Insert into rental
insert into rental (rental_type, apt_num, street, city, province, pc, num_bedrooms, num_bathrooms, laundry_type, has_parking, is_accessible, rent, date_listed) values
('House', NULL, 'King St', 'Toronto', 'ON', 'M5V3D7', 3, 2, 'ensuite', TRUE, TRUE, 1500.00, '2021-01-01'),
('Apartment', 202, 'Granville St', 'Vancouver', 'BC', 'V5K0A1', 2, 1, 'shared', TRUE, FALSE, 1200.00, '2021-02-01'),
('Room', 303, '8 Ave SW', 'Calgary', 'AB', 'T2P5H1', 1, 1, 'ensuite', FALSE, TRUE, 800.00, '2021-03-01'),
('House', NULL, 'Saint Catherine St', 'Montreal', 'QC', 'H3B4G7', 4, 3, 'shared', FALSE, FALSE, 2000.00, '2021-04-01'),
('Apartment', 505, 'Spring Garden Rd', 'Halifax', 'NS', 'B3J3K5', 2, 2, 'ensuite', TRUE, TRUE, 1300.00, '2021-05-01'),
('Room', 606, 'Portage Ave', 'Winnipeg', 'MB', 'R3C0V6', 3, 2, 'shared', TRUE, FALSE, 1600.00, '2021-06-01'),
('House', NULL, '2nd Ave N', 'Saskatoon', 'SK', 'S7K2C9', 1, 1, 'ensuite', FALSE, TRUE, 900.00, '2021-07-01'),
('Apartment', 808, 'Water St', 'St. Johns', 'NL', 'A1C5H5', 4, 3, 'shared', FALSE, FALSE, 2100.00, '2021-08-01'),
('Room', 909, 'Queen St', 'Charlottetown', 'PE', 'C1A1B1', 3, 2, 'ensuite', TRUE, TRUE, 1700.00, '2021-09-01'),
('House', NULL, 'Franklin Ave', 'Yellowknife', 'NT', 'X1A1B2', 2, 1, 'shared', TRUE, FALSE, 1100.00, '2021-10-01');

-- Insert into owns_rental
-- Assuming rental_id's are assigned sequentially starting from 1
insert into owns_rental (owner_id, rental_id) values
('RO001', 1),
('RO006', 1),
('RO002', 2),
('RO007', 2),
('RO003', 3),
('RO004', 4),
('RO005', 5),
('RO001', 6),
('RO002', 7),
('RO003', 8),
('RO004', 9),
('RO005', 10);

-- Insert into manages_rental
-- Assuming manager assignments as per your initial design
insert into manages_rental (manager_id, rental_id, date_started) values
('RM001', 1, '2021-01-01'),
('RM002', 2, '2021-02-01'),
('RM003', 3, '2021-03-01'),
('RM004', 4, '2021-04-01'),
('RM005', 5, '2021-05-01'),
('RM002', 6, '2021-06-01'),
('RM003', 7, '2021-07-01'),
('RM004', 8, '2021-08-01'),
('RM005', 9, '2021-09-01'),
('RM001', 10, '2021-10-01');

-- Insert into house
insert into house (rental_id, house_type, has_fenced_yard) values
(1, 'semi', TRUE),
(4, 'semi', FALSE),
(7, 'semi', TRUE),
(10, 'semi', TRUE);

-- Insert into apartment
insert into apartment (rental_id, has_elevator, floor) values
(2, FALSE, 2),
(5, TRUE, 15),
(8, FALSE, 3);

-- Insert into room
insert into room (rental_id, roommates, has_kitchen_privileges) values
(3, 1, FALSE),
(6, 3, TRUE),
(9, 0, FALSE);

-- Insert into furnishings
insert into furnishings (room_rental_id, furniture_name, quantity) values
(3, 'Desk', 1),
(3, 'Bed', 2),
(6, 'Wardrobe', 2),
(6, 'Chair', 1),
(9, 'Dresser', 3),
(9, 'Shelf', 2);

-- Insert into lease
insert into lease (rental_id, rental_group_id, date_signed, end_date, rent_price) values
(1, '0001', '2021-01-01', '2022-01-01', 1500.00),
(2, '0002', '2021-02-01', '2022-02-01', 1200.00),
(3, '0003', '2021-03-01', '2022-03-01', 800.00),
(4, '0004', '2021-04-01', '2022-04-01', 2000.00),
(5, '0005', '2021-05-01', '2022-05-01', 1300.00),
(6, '0006', '2021-06-01', '2022-06-01', 1600.00),
(7, '0007', '2021-07-01', '2022-07-01', 900.00),
(8, '0008', '2021-08-01', '2022-08-01', 2100.00),
(9, '0009', '2021-09-01', '2022-09-01', 1700.00),
(10, '0010', '2021-10-01', '2022-10-01', 1100.00);




