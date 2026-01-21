// // ১. মাউসের রাইট ক্লিক বন্ধ করা
// document.addEventListener("contextmenu", function (e) {
//     e.preventDefault();
// });

// // ২. কি-বোর্ড শর্টকাট (F12, Ctrl+U, Inspect) বন্ধ করা
// document.onkeydown = function (e) {
//     // F12 বন্ধ করা
//     if (e.keyCode == 123) {
//         return false;
//     }

//     // Ctrl+Shift+I (Inspect Element)
//     if (e.ctrlKey && e.shiftKey && e.keyCode == "I".charCodeAt(0)) {
//         return false;
//     }

//     // Ctrl+Shift+J (Console)
//     if (e.ctrlKey && e.shiftKey && e.keyCode == "J".charCodeAt(0)) {
//         return false;
//     }

//     // Ctrl+U (View Page Source)
//     if (e.ctrlKey && e.keyCode == "U".charCodeAt(0)) {
//         return false;
//     }

//     // Ctrl+Shift+C (Inspect Element Selector)
//     if (e.ctrlKey && e.shiftKey && e.keyCode == "C".charCodeAt(0)) {
//         return false;
//     }

//     // Ctrl+S (Save Page - ঐচ্ছিক, চাইলে বন্ধ করতে পারেন)
//     if (e.ctrlKey && e.keyCode == "S".charCodeAt(0)) {
//         return false;
//     }
// };

// // ৩. ডিবাগার লুপ (যদি কেউ কোনোভাবে কনসোল খোলে, তবে ব্রাউজার বার বার আটকে যাবে)
// setInterval(function () {
//     debugger;
// }, 100);

// // ৪. কনসোল পরিষ্কার রাখা (Console এ কিছু টাইপ করতে চাইলে তা মুছে যাবে)
// setInterval(function () {
//     console.clear();
// }, 500);
