-- =====================================================================
-- Migration: status kolom rekeningwnj -> CHAR(1) 'A' (aktif) / 'N' (nonaktif)
-- =====================================================================
--
-- Sebelumnya status VARCHAR(45) NULL dipakai tidak konsisten: sebagian
-- query lama menganggap status IS NULL sebagai "aktif" (WHERE status IS NULL),
-- sebagian lagi tidak filter status sama sekali. Diseragamkan jadi dua nilai
-- eksplisit supaya semua tempat yang ambil rekeningwnj bisa filter dengan
-- WHERE status = 'A' tanpa ambigu.
UPDATE rekeningwnj SET status = 'A' WHERE status IS NULL OR status NOT IN ('A', 'N');
ALTER TABLE rekeningwnj MODIFY COLUMN status CHAR(1) NOT NULL DEFAULT 'A';
