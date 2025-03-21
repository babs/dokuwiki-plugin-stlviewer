# Dokuwiki STL Viewer plugin


Embed STL render using Viewstl.com excellent library v1.13

## Options

Options are URL encoded a=1&b=2 etc

- manual: Do not render automatically
  - Example: ``manual=true`` to show a link that triggers the loading of the model
- noop: Disable rendering
  - Example: ``noop=true`` to show only a link of the .stl file
- s: Size in pixel (defines height and width in one call)
  - Example: ``s=400`` for a frame 400 x 400 pixels
- h: Height in pixel
  - Example: ``h=400`` for a height of 400 pixels
- w: Width in pixel
  - Example: ``w=400`` for a width of 400 pixels
- color: Color of the model
  - Example: ``color=#00ff00`` for a green model
- bgcolor: Background color
  - Example: ``color=#ffffff`` for a white background
- display: Set model display/shading ("flat", "smooth" or "wireframe")
  - Example: ``model=wireframe``

Example for a complete syntax: ``{{my_model.stl?h=400&w=600&bgcolor=#cccccc&color=#eb984e&display=wireframe&noop=true|My model title}}``

## Changelog

* **2025-03-20**
  * model display options added (@Ragos81)
* **2024-04-09**
  * fix background color (@Ragos81)
* **2022-09-07**
  * update stlviewer to v1.13 (@KaiMartin)
  * short download link (@Laserlicht)
* **2022-01-25**
  * Fix for subfolder path (@Juergen-aus-Zuendorf)
* **2020-04-25**
  * Initial release
