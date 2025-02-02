import pandas as pd
import mysql.connector
import sys

# Path file Excel
file_path = sys.argv[1]

# Baca file Excel
df = pd.read_excel(file_path)

# Koneksi ke database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="masada"
)
cursor = conn.cursor()

# Memasukkan data PO
for _, row in df.iterrows():
    # Memasukkan data ke tabel po
    query_po = """
        INSERT INTO po (po_no, store_code, store_name, total, created_at)
        VALUES (%s, %s, %s, %s, %s)
    """
    cursor.execute(query_po, (row['po_no'], row['store_code'], row['store_name'], row['total'], row['created_at']))

    # Memasukkan data ke tabel det_po
    query_det_po = """
        INSERT INTO det_po (po_no, supplier_item_no, item_description, quantity, unit_price, amount)
        VALUES (%s, %s, %s, %s, %s, %s)
    """
    cursor.execute(query_det_po, (
        row['po_no'], row['supplier_item_no'], row['item_description'],
        row['quantity'], row['unit_price'], row['amount']
    ))

# Commit perubahan dan tutup koneksi
conn.commit()
cursor.close()
conn.close()

print("Data berhasil dimasukkan ke database.")
