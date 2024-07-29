# import os
# from flask import Flask, request, jsonify, render_template, redirect, url_for
# from werkzeug.utils import secure_filename
# from mindee import Client, product

# app = Flask(__name__)
# app.config['UPLOAD_FOLDER'] = 'user\doc'
# app.config['ALLOWED_EXTENSIONS'] = {'pdf', 'png', 'jpg', 'jpeg'}

# # Initialize the Mindee client with the API key
# mindee_client = Client(api_key="82a38375b441ab5d64bc389baa87f04a")

# # Function to check if a filename has an allowed extension
# def allowed_file(filename):
#     return '.' in filename and \
#            filename.rsplit('.', 1)[1].lower() in app.config['ALLOWED_EXTENSIONS']

# @app.route('/upload', methods=['POST'])
# def upload_file():
#     # Check if the POST request has the file part
#     if 'file' not in request.files:
#         return jsonify({'error': 'No file part'})

#     file = request.files['file']

#     # If user does not select file, browser also submits an empty part without filename
#     if file.filename == '':
#         return jsonify({'error': 'No selected file'})

#     if file and allowed_file(file.filename):
#         filename = secure_filename(file.filename)
#         file_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
#         file.save(file_path)

#         # Parse the document using the FinancialDocumentV1 product
#         input_doc = mindee_client.source_from_path(file_path)
#         result = mindee_client.parse(product.FinancialDocumentV1, input_doc)

#         # Extract relevant information from the parsed result
#         document = result.document.inference.prediction

#         key_data = {
#             "Document Number": document.invoice_number.value if document.invoice_number else "N/A",
#             "Purchase Date": document.date.value if document.date else "N/A",
#             "Due Date": document.due_date.value if document.due_date else "N/A",
#             "Total Amount": document.total_amount.value if document.total_amount else "N/A",
#             "Supplier Name": document.supplier_name.value if document.supplier_name else "N/A"
#         }

#         return jsonify(key_data)

#     else:
#         return jsonify({'error': 'File type not allowed'})

# if __name__ == '__main__':
#     app.run(debug=True)
