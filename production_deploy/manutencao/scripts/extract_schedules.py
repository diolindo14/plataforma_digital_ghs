import pdfplumber

pdf_path = r"c:\xampp\htdocs\green\img\HORARIO_2025_2026_2ºS.pdf"

try:
    with pdfplumber.open(pdf_path) as pdf:
        for idx, page in enumerate(pdf.pages):
            text = page.extract_text()
            print(f"\n--- PAGINA {idx+1} ---")
            print(text)
except Exception as e:
    print(f"Erro: {e}")
