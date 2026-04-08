const puppeteer = require('puppeteer');
(async () => {
    const browser = await puppeteer.launch({ headless: 'new' });
    const page = await browser.newPage();
    await page.setViewport({ width: 1440, height: 900 });
    try {
        await page.goto('https://cloudnium.net', { waitUntil: 'networkidle2', timeout: 30000 });
        await page.screenshot({ path: 'cloudnium_full.png', fullPage: true });
        console.log('✅ Screenshot saved to cloudnium_full.png');
    } catch (e) {
        console.log('❌ Error taking screenshot:', e.message);
        // Fallback to fetching HTML if screenshot fails due to bot protection
        console.log('Attempting to fetch HTML...');
        try {
            const html = await page.content();
            const fs = require('fs');
            fs.writeFileSync('cloudnium.html', html);
            console.log('✅ HTML saved to cloudnium.html');
        } catch (e2) {
             console.log('❌ Error saving HTML:', e2.message);
        }
    }
    await browser.close();
})();
