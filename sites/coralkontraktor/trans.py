import sys
from PIL import Image

def make_transparent(input_path, output_path):
    img = Image.open(input_path)
    img = img.convert("RGBA")
    datas = img.getdata()
    new_data = []
    
    threshold = 240
    for item in datas:
        # Check if the pixel is near white
        if item[0] >= threshold and item[1] >= threshold and item[2] >= threshold:
            # Change near-white to transparent
            new_data.append((255, 255, 255, 0))
        else:
            new_data.append(item)
            
    img.putdata(new_data)
    img.save(output_path, "PNG")

make_transparent("/home/devsant/gitweb/coralkontraktor/logo.jpeg", "/home/devsant/gitweb/coralkontraktor/logo.png")
