const cacheName = 'masar-v1';
const assets = [
    '/',
    '/student/dashboard', // الصفحة الرئيسية
    '/student/profile',   // البروفايل
    '/student/financial', // المالية
    '/css/app.css',       // ملف التنسيق
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css' 
];

// هذه الدالة هي التي تخزن الصفحات يدوياً
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(cacheName).then(cache => {
            console.log('جاري تخزين الصفحات للعمل بدون إنترنت...');
            return cache.addAll(assets);
        })
    );
});
self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});