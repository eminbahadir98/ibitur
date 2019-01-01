file_names = ["../drop_tables.sql",
              "../create_tables.sql",
              "../create_views.sql",
              "../populate_tables.sql"]

input_files = []
for file_name in file_names:
    input_files.append(open(file_name, "r"))

output_str = ""
for input_file in input_files:
    output_str += input_file.read()

output_file = open("../RESET_ALL.sql", "w")
output_file.write(output_str)

for input_file in input_files:
    input_file.close()
output_file.close()
