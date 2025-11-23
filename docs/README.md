# 🚀 Quick Start - Instance ID Fix

## Problem
Instance ID tidak berubah saat create WhatsApp profile baru di `https://blast.myarchery.id/whatsapp_profiles/oauth`

## Solution  
Frontend sekarang call backend API untuk generate unique instance IDs.

---

## 📁 Documentation

| File | Description |
|------|-------------|
| `SUMMARY.md` | **START HERE** - Overview lengkap |
| `18-complete-fix-unique-instance-id.md` | Technical documentation detail |
| `DEPLOYMENT-CHECKLIST.md` | Step-by-step deployment guide |
| `17-fix-instance-id-tidak-berubah.md` | Backend implementation guide |

---

## ⚡ Quick Deploy

### Backend (Node.js)
```bash
ssh root@103.82.92.157
cd /www/wwwroot/api-blast
git pull origin main
pkill -f "node app.js"
nohup node app.js > logs/server.log 2>&1 &
```

### Frontend (PHP)
```bash
ssh user@blast.myarchery.id
cd /path/to/myarchery-blast
git pull origin main
rm -rf writable/cache/*
```

### Verify
```bash
# Test API
curl http://103.82.92.157:8000/generate-instance-id

# Test Frontend
curl https://blast.myarchery.id/whatsapp_profiles/oauth
```

---

## 📊 Files Changed

### Backend
- `/www/wwwroot/api-blast/app.js` - New endpoint

### Frontend  
- `inc/core/Whatsapp/Helpers/Whatsapp_helper.php` - Helper function
- `inc/core/Whatsapp_profiles/Controllers/Whatsapp_profiles.php` - Controller update

---

## ✅ Success Criteria

- [x] Code written and tested locally
- [x] Documentation complete
- [x] Committed to GitHub
- [ ] Deployed to production
- [ ] Verified in production

---

## 📞 Need Help?

Read `SUMMARY.md` untuk overview lengkap atau `DEPLOYMENT-CHECKLIST.md` untuk deployment guide.
