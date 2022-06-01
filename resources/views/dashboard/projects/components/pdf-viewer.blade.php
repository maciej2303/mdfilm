<div class="text-center pdf-toolbar">
    <input type="hidden" id="hiddenPDF" value="{{$projectElementComponentVersion->pdf}}">
    <fieldset id="btn-group" class="btn-group invisible">
        <button id="prev" class="btn btn-white">
            <i class="fa fa-long-arrow-left"></i> <span class="d-none d-sm-inline">Previous</span>
        </button>
        <button id="next" class="btn btn-white">
            <i class="fa fa-long-arrow-right"></i> <span class="d-none d-sm-inline">Next</span>
        </button>
        <button id="zoomin" class="btn btn-white">
            <i class="fa fa-search-plus"></i> <span class="d-none d-sm-inline">Zoom In</span>
        </button>
        <button id="zoomout" class="btn btn-white">
            <i class="fa fa-search-minus"></i> <span class="d-none d-sm-inline">Zoom Out</span>
        </button>
        <button id="zoomfit" class="btn btn-white"> 100%</button>
        <span class="btn btn-white hidden-xs">Page: </span>

        <div class="input-group">
            <input type="text" class="form-control" id="page_num">

            <div class="input-group-append">
                <button type="button" class="btn btn-white" id="page_count">/
                    </button>
            </div>
        </div>
        <a href="{{$projectElementComponentVersion->pdf}}" download target="_blank" class="btn btn-white">
            <i class="fa fa-download" aria-hidden="true"></i>
        </a>

    </fieldset>
</div>

<div class="text-center m-t-md" style="overflow: scroll; max-height: 1200px">
    <canvas id="the-canvas" class="pdfcanvas border-left-right border-top-bottom b-r-md"></canvas>
</div>



@push('js')
<script src="{{asset('js/plugins/bs-custom-file/bs-custom-file-input.min.js')}}"></script>
<script src="{{asset('js/plugins/pdfjs/pdf.js')}}"></script>
<script id="script">
    //
    // If absolute URL from the remote server is provided, configure the CORS
    // header on that server.
    //

    let url = $('#hiddenPDF').val();
    var pdfDoc = null,
        pageNum = 1,
        pageRendering = false,
        pageNumPending = null,
        scale = 1,
        zoomRange = 0.25,
        canvas = document.getElementById('the-canvas'),
        ctx = canvas.getContext('2d');
    /**
     * Get page info from document, resize canvas accordingly, and render page.
     * @param num Page number.
     */
    async function renderPage(num, scale) {
        return new Promise((resolve, reject) => {
            pageRendering = true;
            // Using promise to fetch the page
            pdfDoc.getPage(num).then(function (page) {
                var viewport = page.getViewport(scale);
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                // Render PDF page into canvas context
                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);

                // Wait for rendering to finish
                renderTask.promise.then(function () {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        // New page rendering is pending
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                    resolve();
                });
            });
            // Update page counters
            document.getElementById('page_num').value = num;

        } )
    }

    /**
     * If another page rendering in progress, waits until the rendering is
     * finised. Otherwise, executes rendering immediately.
     */
    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num, scale);
        }
    }

    /**
     * Displays previous page.
     */
    async function onPrevPage() {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        document.getElementById('btn-group').setAttribute('disabled', true);
        await renderPage(pageNum, scale)
        document.getElementById('btn-group').removeAttribute('disabled');
    }
    document.getElementById('prev').addEventListener('click', onPrevPage);

    /**
     * Displays next page.
     */
    async function onNextPage() {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        pageNum++;
        document.getElementById('btn-group').setAttribute('disabled', true);
        await renderPage(pageNum, scale)
        document.getElementById('btn-group').removeAttribute('disabled');
    }
    document.getElementById('next').addEventListener('click', onNextPage);

    /**
     * Zoom in page.
     */
    async function onZoomIn() {
        if (scale >= pdfDoc.scale) {
            return;
        }
        scale += zoomRange;
        var num = pageNum;
        document.getElementById('btn-group').setAttribute('disabled', true);
        await renderPage(num, scale)
        document.getElementById('btn-group').removeAttribute('disabled');


    }
    document.getElementById('zoomin').addEventListener('click', onZoomIn);

    /**
     * Zoom out page.
     */
    async function onZoomOut() {
        if (scale >= pdfDoc.scale) {
            return;
        }
        if (scale < 0.3) {
            return;
        }
        scale -= zoomRange;
        var num = pageNum;
        document.getElementById('btn-group').setAttribute('disabled', true);
        await renderPage(num, scale);
        document.getElementById('btn-group').removeAttribute('disabled');
    }
    document.getElementById('zoomout').addEventListener('click', onZoomOut);

    /**
     * Zoom fit page.
     */
    async function onZoomFit() {
        if (scale >= pdfDoc.scale) {
            return;
        }
        scale = 1;
        var num = pageNum;
        document.getElementById('btn-group').setAttribute('disabled', true);
        await renderPage(num, scale);
        document.getElementById('btn-group').removeAttribute('disabled');

    }
    document.getElementById('zoomfit').addEventListener('click', onZoomFit);


    /**
     * Asynchronously downloads PDF.
     */
    PDFJS.getDocument(url).then(function (pdfDoc_) {
        pdfDoc = pdfDoc_;
        var documentPagesNumber = pdfDoc.numPages;
        document.getElementById('page_count').textContent = '/ ' +
            documentPagesNumber;

        $('#page_num').on('change', function () {
            var pageNumber = Number($(this).val());

            if (pageNumber > 0 && pageNumber <= documentPagesNumber) {
                queueRenderPage(pageNumber, scale);
            }

        });

        // Initial/first page rendering
        renderPage(pageNum, scale);
        document.getElementById('btn-group').classList.remove("invisible");
    });
</script>
@endpush
