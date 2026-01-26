(() => {
    const theme = localStorage.getItem('theme')

    if (theme === 'dark') {
        document.documentElement.classList.add('dark')
    } else if (theme === 'light') {
        document.documentElement.classList.remove('dark')
    } else if (!theme) {
        document.documentElement.classList.toggle(
            'dark',
            window.matchMedia('(prefers-color-scheme: dark)').matches
        )
    }
})()

import QRCode from 'qrcode';
import { Html5Qrcode } from 'html5-qrcode';

window.QRCode = QRCode;
window.Html5Qrcode = Html5Qrcode;