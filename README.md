# Browser cache file recovery

Recover the original text of a file from the cache file browsers hold.  
You can find the project running online at http://codeams.ml/file-recovery/

**Notes:**
  1. This project has been tested only in Google Chrome 53 and Mozilla Firefox 49.
  1. This project can recover only text-base files. Binary files like images or videos are not supported.
  1. The online site's file recovery is done server side but it does not keep any kind of record of the files sent.
  1. Feel free to download the project and use it in your own pc or server.

## Instructions

1. Go to your browser's cache:
  * Google Chrome: Type `chrome://cache` in the adress bar.
  * Mozilla Firefox: Type `about:cache` in the adress. Then click "List Cache Entries" in the section your file is located. They are usually in the disk which you can access directly through `about:cache?storage=disk`.
1. Search your file's key (URL) and click it.
1. Press `ctrl + A` or `⌘ + A` to select the entire content of the file and copy it.
1. Insert the copied text in the textbox you'll find in [codeams.ml/file-recovery/](http://codeams.ml/file-recovery/).

Note:  You can do the last step in your own pc or server if you want to.

## Known issues

1. If you're getting an *Unknown file type* error or a *No results* error try to copy only the content of the file. In chrome it's the second section only (the sections are divided by lines) and in firefox it's everything under the line where the weird numbers start.
1. The script will ommit the last 0 to 15 characters of your file. See [the issue](https://github.com/codeams/browser-cache-file-recovery/issues/1/).

## License

MIT License

Copyright (c) 2016 Alejandro Montañez

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
