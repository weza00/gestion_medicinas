-- ------------------------------------------------------------------
-- Script de datos de prueba para "gestion_medicinas"
-- Inserta categorías y medicamentos para testing/desarrollo
-- ------------------------------------------------------------------

USE `gestion_medicinas`;

-- -----------------------------------------------------
-- Insertar CATEGORÍAS
-- -----------------------------------------------------
INSERT INTO `categorias` (`nombre`, `descripcion`) VALUES
('Analgésicos', 'Medicamentos para aliviar el dolor'),
('Antibióticos', 'Medicamentos para combatir infecciones bacterianas'),
('Antiinflamatorios', 'Medicamentos para reducir inflamación'),
('Cardiovasculares', 'Medicamentos para el sistema cardiovascular'),
('Respiratorios', 'Medicamentos para el sistema respiratorio'),
('Digestivos', 'Medicamentos para el sistema digestivo'),
('Neurológicos', 'Medicamentos para el sistema nervioso'),
('Endocrinos', 'Medicamentos para trastornos hormonales'),
('Dermatológicos', 'Medicamentos para la piel'),
('Oftalmológicos', 'Medicamentos para los ojos'),
('Vitaminas y Minerales', 'Suplementos vitamínicos y minerales'),
('Antihistamínicos', 'Medicamentos para alergias');

-- -----------------------------------------------------
-- Insertar MEDICAMENTOS
-- -----------------------------------------------------

-- ANALGÉSICOS (categoria_id = 1)
INSERT INTO `medicamentos` (`categoria_id`, `nombre`, `descripcion`, `presentacion`, `precio`, `stock`, `estado`) VALUES
(1, 'Paracetamol 500mg', 'Analgésico y antipirético de uso común', 'Caja x 20 tabletas', 8.50, 150, 1),
(1, 'Ibuprofeno 400mg', 'Analgésico antiinflamatorio no esteroideo', 'Caja x 30 cápsulas', 12.00, 120, 1),
(1, 'Aspirina 500mg', 'Ácido acetilsalicílico para dolor y fiebre', 'Frasco x 100 tabletas', 15.75, 80, 1),
(1, 'Ketorolaco 10mg', 'Analgésico potente para dolor moderado a severo', 'Caja x 20 tabletas', 25.00, 60, 1),
(1, 'Tramadol 50mg', 'Analgésico opioide para dolor moderado', 'Caja x 30 cápsulas', 35.00, 40, 1),

-- ANTIBIÓTICOS (categoria_id = 2)
(2, 'Amoxicilina 500mg', 'Antibiótico de amplio espectro tipo penicilina', 'Caja x 21 cápsulas', 18.00, 100, 1),
(2, 'Azitromicina 500mg', 'Antibiótico macrólido para infecciones respiratorias', 'Caja x 6 tabletas', 22.50, 75, 1),
(2, 'Ciprofloxacino 500mg', 'Antibiótico fluoroquinolona para infecciones urinarias', 'Caja x 14 tabletas', 28.00, 65, 1),
(2, 'Clindamicina 300mg', 'Antibiótico para infecciones por anaerobios', 'Caja x 16 cápsulas', 32.00, 45, 1),
(2, 'Cefalexina 500mg', 'Antibiótico cefalosporina de primera generación', 'Caja x 28 cápsulas', 20.00, 85, 1),

-- ANTIINFLAMATORIOS (categoria_id = 3)
(3, 'Diclofenaco 50mg', 'Antiinflamatorio no esteroideo potente', 'Caja x 20 tabletas', 14.00, 90, 1),
(3, 'Naproxeno 250mg', 'AINE de larga duración', 'Caja x 30 tabletas', 16.50, 70, 1),
(3, 'Meloxicam 15mg', 'Antiinflamatorio selectivo COX-2', 'Caja x 30 tabletas', 19.00, 55, 1),
(3, 'Prednisolona 5mg', 'Corticosteroide antiinflamatorio', 'Caja x 30 tabletas', 12.00, 40, 1),

-- CARDIOVASCULARES (categoria_id = 4)
(4, 'Enalapril 10mg', 'Inhibidor de la ECA para hipertensión', 'Caja x 30 tabletas', 15.00, 120, 1),
(4, 'Amlodipino 5mg', 'Bloqueador de canales de calcio', 'Caja x 30 tabletas', 18.00, 100, 1),
(4, 'Metoprolol 50mg', 'Beta bloqueador para hipertensión y arritmias', 'Caja x 30 tabletas', 22.00, 80, 1),
(4, 'Atorvastatina 20mg', 'Estatina para reducir colesterol', 'Caja x 30 tabletas', 45.00, 60, 1),
(4, 'Losartán 50mg', 'Antagonista de receptor de angiotensina', 'Caja x 30 tabletas', 20.00, 90, 1),

-- RESPIRATORIOS (categoria_id = 5)
(5, 'Salbutamol 100mcg', 'Broncodilatador para asma', 'Inhalador x 200 dosis', 25.00, 70, 1),
(5, 'Montelukast 10mg', 'Antileucotrieno para asma y rinitis', 'Caja x 30 tabletas', 35.00, 50, 1),
(5, 'Dextrometorfano 15mg', 'Antitusivo para tos seca', 'Jarabe x 120ml', 12.00, 80, 1),
(5, 'Budesonida 200mcg', 'Corticosteroide inhalado', 'Inhalador x 120 dosis', 55.00, 40, 1),

-- DIGESTIVOS (categoria_id = 6)
(6, 'Omeprazol 20mg', 'Inhibidor de bomba de protones', 'Caja x 30 cápsulas', 16.00, 110, 1),
(6, 'Ranitidina 150mg', 'Antagonista H2 para acidez', 'Caja x 30 tabletas', 10.00, 95, 1),
(6, 'Metoclopramida 10mg', 'Procinético antiemético', 'Caja x 30 tabletas', 8.00, 85, 1),
(6, 'Loperamida 2mg', 'Antidiarreico', 'Caja x 20 cápsulas', 12.00, 75, 1),
(6, 'Simeticona 80mg', 'Antiflatulento para gases', 'Caja x 30 tabletas masticables', 9.00, 100, 1),

-- NEUROLÓGICOS (categoria_id = 7)
(7, 'Carbamazepina 200mg', 'Antiepiléptico y estabilizador del ánimo', 'Caja x 50 tabletas', 25.00, 30, 1),
(7, 'Fenitoína 100mg', 'Antiepiléptico clásico', 'Caja x 100 cápsulas', 20.00, 25, 1),
(7, 'Gabapentina 300mg', 'Antiepiléptico para neuropatía', 'Caja x 30 cápsulas', 40.00, 35, 1),
(7, 'Alprazolam 0.5mg', 'Benzodiacepina ansiolítica', 'Caja x 30 tabletas', 18.00, 50, 1),

-- ENDOCRINOS (categoria_id = 8)
(8, 'Metformina 850mg', 'Antidiabético oral primera línea', 'Caja x 60 tabletas', 15.00, 120, 1),
(8, 'Glibenclamida 5mg', 'Sulfonilurea antidiabética', 'Caja x 50 tabletas', 12.00, 80, 1),
(8, 'Levotiroxina 50mcg', 'Hormona tiroidea sintética', 'Caja x 50 tabletas', 20.00, 70, 1),
(8, 'Insulina NPH', 'Insulina de acción intermedia', 'Vial x 10ml (100 UI/ml)', 65.00, 30, 1),

-- DERMATOLÓGICOS (categoria_id = 9)
(9, 'Hidrocortisona 1%', 'Corticosteroide tópico suave', 'Tubo x 30g', 15.00, 60, 1),
(9, 'Clotrimazol 1%', 'Antifúngico tópico', 'Tubo x 20g', 12.00, 70, 1),
(9, 'Mupirocina 2%', 'Antibiótico tópico', 'Tubo x 15g', 18.00, 50, 1),
(9, 'Ketoconazol 2%', 'Antifúngico para dermatitis seborreica', 'Shampoo x 100ml', 22.00, 40, 1),

-- OFTALMOLÓGICOS (categoria_id = 10)
(10, 'Timolol 0.5%', 'Gotas para glaucoma', 'Frasco x 5ml', 28.00, 25, 1),
(10, 'Ciprofloxacino 0.3%', 'Antibiótico oftálmico', 'Frasco x 5ml', 20.00, 35, 1),
(10, 'Lágrimas artificiales', 'Lubricante ocular', 'Frasco x 15ml', 12.00, 80, 1),
(10, 'Dexametasona 0.1%', 'Corticosteroide oftálmico', 'Frasco x 5ml', 25.00, 30, 1),

-- VITAMINAS Y MINERALES (categoria_id = 11)
(11, 'Complejo B', 'Vitaminas del complejo B', 'Caja x 100 tabletas', 18.00, 90, 1),
(11, 'Vitamina C 500mg', 'Ácido ascórbico antioxidante', 'Caja x 60 tabletas', 15.00, 100, 1),
(11, 'Vitamina D3 1000 UI', 'Colecalciferol para huesos', 'Caja x 30 cápsulas', 22.00, 70, 1),
(11, 'Calcio + Magnesio', 'Suplemento para huesos', 'Caja x 60 tabletas', 25.00, 65, 1),
(11, 'Hierro 65mg', 'Sulfato ferroso para anemia', 'Caja x 100 tabletas', 12.00, 85, 1),
(11, 'Ácido Fólico 5mg', 'Vitamina B9 para embarazo', 'Caja x 30 tabletas', 8.00, 120, 1),

-- ANTIHISTAMÍNICOS (categoria_id = 12)
(12, 'Loratadina 10mg', 'Antihistamínico no sedante', 'Caja x 30 tabletas', 14.00, 95, 1),
(12, 'Cetirizina 10mg', 'Antihistamínico de segunda generación', 'Caja x 30 tabletas', 16.00, 85, 1),
(12, 'Difenhidramina 25mg', 'Antihistamínico clásico sedante', 'Caja x 20 cápsulas', 10.00, 75, 1),
(12, 'Fexofenadina 120mg', 'Antihistamínico no sedante potente', 'Caja x 30 tabletas', 25.00, 60, 1);

-- -----------------------------------------------------
-- Verificación de datos insertados
-- -----------------------------------------------------
SELECT 'Categorías insertadas:', COUNT(*) as total FROM categorias;
SELECT 'Medicamentos insertados:', COUNT(*) as total FROM medicamentos;

-- Mostrar resumen por categoría
SELECT 
    c.nombre as categoria,
    COUNT(m.id) as cantidad_medicamentos,
    MIN(m.precio) as precio_min,
    MAX(m.precio) as precio_max,
    AVG(m.precio) as precio_promedio
FROM categorias c
LEFT JOIN medicamentos m ON c.id = m.categoria_id
GROUP BY c.id, c.nombre
ORDER BY c.nombre;