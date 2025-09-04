-- Seed data for Hardware Inventory Management System
USE hardware_inventory;

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@hardware.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin'),
('manager', 'manager@hardware.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Inventory Manager', 'manager'),
('employee', 'employee@hardware.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Warehouse Employee', 'employee');

-- Insert hardware categories
INSERT INTO categories (name, description, icon) VALUES
('Processadores', 'CPUs e processadores para computadores', 'cpu'),
('Memória RAM', 'Módulos de memória RAM DDR3, DDR4, DDR5', 'memory'),
('Placas de Vídeo', 'Placas gráficas e GPUs', 'gpu'),
('Armazenamento', 'HDDs, SSDs e dispositivos de armazenamento', 'storage'),
('Placas-Mãe', 'Motherboards para diferentes sockets', 'motherboard'),
('Fontes', 'Fontes de alimentação ATX e modular', 'power'),
('Gabinetes', 'Cases e gabinetes para PC', 'case'),
('Periféricos', 'Teclados, mouses, monitores', 'peripheral'),
('Rede', 'Equipamentos de rede e conectividade', 'network'),
('Refrigeração', 'Coolers, water coolers e ventoinhas', 'cooling');

-- Insert suppliers
INSERT INTO suppliers (name, contact_person, email, phone, address) VALUES
('TechDistribuidor Ltda', 'João Silva', 'joao@techdist.com', '(11) 9999-1234', 'Rua da Tecnologia, 123 - São Paulo, SP'),
('Hardware Solutions', 'Maria Santos', 'maria@hwsolutions.com', '(21) 8888-5678', 'Av. das Américas, 456 - Rio de Janeiro, RJ'),
('PC Components Brasil', 'Carlos Oliveira', 'carlos@pcbrasil.com', '(31) 7777-9012', 'Rua dos Componentes, 789 - Belo Horizonte, MG'),
('Eletrônicos Premium', 'Ana Costa', 'ana@premium.com', '(41) 6666-3456', 'Av. Paraná, 321 - Curitiba, PR');

-- Insert sample hardware items
INSERT INTO hardware_items (name, description, category_id, supplier_id, sku, unit_price, quantity_in_stock, minimum_stock_level, location) VALUES
-- Processadores
('Intel Core i7-13700K', 'Processador Intel Core i7 13ª geração, 16 cores, 3.4GHz', 1, 1, 'CPU-I7-13700K', 2299.99, 15, 5, 'Estoque A1'),
('AMD Ryzen 7 7700X', 'Processador AMD Ryzen 7, 8 cores, 4.5GHz', 1, 2, 'CPU-R7-7700X', 1899.99, 12, 3, 'Estoque A2'),
('Intel Core i5-13400F', 'Processador Intel Core i5 13ª geração, 10 cores, 2.5GHz', 1, 1, 'CPU-I5-13400F', 1299.99, 25, 8, 'Estoque A3'),

-- Memória RAM
('Corsair Vengeance LPX 16GB DDR4', 'Memória RAM 16GB DDR4 3200MHz', 2, 3, 'RAM-CORS-16GB-DDR4', 399.99, 30, 10, 'Estoque B1'),
('Kingston Fury Beast 32GB DDR5', 'Memória RAM 32GB DDR5 5600MHz', 2, 2, 'RAM-KING-32GB-DDR5', 899.99, 18, 5, 'Estoque B2'),
('G.Skill Ripjaws V 8GB DDR4', 'Memória RAM 8GB DDR4 3000MHz', 2, 4, 'RAM-GSKILL-8GB-DDR4', 199.99, 45, 15, 'Estoque B3'),

-- Placas de Vídeo
('NVIDIA RTX 4070 Ti', 'Placa de vídeo NVIDIA GeForce RTX 4070 Ti 12GB', 3, 1, 'GPU-RTX-4070TI', 4299.99, 8, 2, 'Estoque C1'),
('AMD RX 7800 XT', 'Placa de vídeo AMD Radeon RX 7800 XT 16GB', 3, 2, 'GPU-RX-7800XT', 3799.99, 6, 2, 'Estoque C2'),
('NVIDIA GTX 1660 Super', 'Placa de vídeo NVIDIA GeForce GTX 1660 Super 6GB', 3, 3, 'GPU-GTX-1660S', 1299.99, 20, 5, 'Estoque C3'),

-- Armazenamento
('Samsung 980 PRO 1TB NVMe', 'SSD NVMe M.2 1TB Samsung 980 PRO', 4, 1, 'SSD-SAM-980PRO-1TB', 599.99, 35, 10, 'Estoque D1'),
('WD Black SN850X 2TB', 'SSD NVMe M.2 2TB Western Digital Black', 4, 4, 'SSD-WD-SN850X-2TB', 1199.99, 15, 5, 'Estoque D2'),
('Seagate Barracuda 2TB HDD', 'HD SATA 2TB 7200RPM Seagate Barracuda', 4, 3, 'HDD-SEA-2TB', 299.99, 40, 12, 'Estoque D3');

-- Insert some stock movements for demonstration
INSERT INTO stock_movements (hardware_item_id, movement_type, quantity, previous_quantity, new_quantity, reason, user_id) VALUES
(1, 'in', 10, 5, 15, 'Recebimento de compra PO-001', 1),
(2, 'in', 8, 4, 12, 'Recebimento de compra PO-002', 1),
(3, 'out', 2, 27, 25, 'Venda para cliente', 2),
(4, 'in', 20, 10, 30, 'Reposição de estoque', 1),
(5, 'out', 1, 19, 18, 'Venda para cliente', 2);
