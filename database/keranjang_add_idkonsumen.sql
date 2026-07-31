-- `keranjang` only had idmitra/idagen/idreseller/idmarketer (distributor-side
-- ownership columns). Adding idkonsumen so the new konsumen/ area can add
-- items to cart the same way distributor/add_chart2.php does.
ALTER TABLE `keranjang`
    ADD COLUMN `idkonsumen` VARCHAR(15) NULL AFTER `idmarketer`;
