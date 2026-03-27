import pdfplumber
import re

pdf_path = r"c:\xampp\htdocs\green\img\HORARIO_2025_2026_2ºS.pdf"

print("Lendo o PDF: " + pdf_path)
try:
    with pdfplumber.open(pdf_path) as pdf:
        found_4t1 = False
        for page_num, page in enumerate(pdf.pages):
            text = page.extract_text()
            if text and ("4T1" in text or "GHS-4T1" in text):
                found_4t1 = True
                print(f"\n--- PÁGINA {page_num+1} (Contém 4T1) ---")
                lines = text.split('\n')
                for line in lines:
                    if "Redes" in line or "RD" in line or "Digitais" in line:
                        print("  [Encontrado]:", line)
                print("---------------------------\n")
        
        if not found_4t1:
            print("Não foi possível encontrar menção a '4T1' de forma legível no texto do PDF.")
            print("Pode ser que o PDF seja uma imagem, tentarei imprimir todo o texto extraído da primeira página.")
            if len(pdf.pages) > 0:
                print(pdf.pages[0].extract_text()[:500])
except Exception as e:
    print(f"Erro ao processar PDF: {e}")
