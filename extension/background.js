chrome.action.onClicked.addListener((tab) => {
    chrome.scripting.executeScript({
        target: { tabId: tab.id },
        function: findPdfOnPage
    }, (results) => {
        if (results && results[0].result) {
            const pdfUrl = results[0].result;
            startDownload(pdfUrl);
        } else {
            console.log("No PDF found on this page.");
        }
    });
});

function findPdfOnPage() {
    const host = window.location.hostname;

    // science direct
    if (host.includes('sciencedirect.com')) {
        // viewer page case
        const pdfIframe = document.querySelector('iframe#pdf-iframe') ||
            document.querySelector('iframe[src*="pdfft"]');
        if (pdfIframe) return pdfIframe.src;

        // abstract page
        const canonical = document.querySelector('link[rel="canonical"]')?.href;
        if (canonical && canonical.includes('pii/')) {
            const pii = canonical.split('pii/')[1].split('/')[0].split('?')[0];
            return `https://sciencedirect.com{pii}/pdfft?is_scidir_auth=true`;
        }
    } // none of this works

    // meta tags
    const meta = document.querySelector('meta[name="citation_pdf_url"]');
    if (meta) return meta.content;

    // link ending with .pdf
    const link = Array.from(document.querySelectorAll('a'))
        .find(a => a.href.toLowerCase().endsWith('.pdf'));

    return link ? link.href : null;
}

// file system
function startDownload(url) {
    // generate folder name
    const rootFolder = '';
    const randomFolder = Math.random().toString(36).substring(7);
    const path = `${rootFolder}${randomFolder}/article.pdf`;

    chrome.downloads.download({
        url: url,
        filename: path,
        saveAs: false   // dont show dialog
    }, (downloadId) => {
        console.log("Download started! ID:", downloadId, "Path:", path);
    });
}
