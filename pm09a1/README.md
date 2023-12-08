# Updating firmware on SSD PM9A1

GXA use for SSD's less or equal than 1TB
GXB use for SSD's greater than 1TB

## Process

1. Clone repo
2. Find your SSD `./EPToolkit -L`
3. Load FW to SSD `./EPToolkit -d 0:c -ND -p ./GXA7701Q_Noformat.bin -a 1 -s 0`
   Where `0:c` is your disk number
4. Commit FW to SSD `./EPToolkit -d 0:c -NC -a 3 -s 0`
5. Check FW updated `./EPToolkit -L`
