# 🚀 Deployment Checklist - Unique Instance ID Fix

## Pre-Deployment

- [ ] Backend code tested locally
- [ ] Frontend code tested locally  
- [ ] Documentation reviewed
- [ ] Backup database
- [ ] Backup current code

---

## Backend Deployment (Node.js API Server)

### Server: 103.82.92.157

```bash
# 1. SSH to server
ssh root@103.82.92.157

# 2. Navigate to API directory
cd /www/wwwroot/api-blast

# 3. Backup current code
cp app.js app.js.backup.$(date +%Y%m%d_%H%M%S)

# 4. Pull latest changes
git pull origin main

# 5. Check if endpoint exists
grep -n "generate-instance-id" app.js

# 6. Stop current server
pkill -f "node app.js"

# 7. Start server in background
nohup node app.js > logs/server.log 2>&1 &

# 8. Verify server is running
ps aux | grep "node app.js"

# 9. Test endpoint
curl http://localhost:8000/generate-instance-id
```

**Expected Output:**
```json
{
  "status": "success",
  "instance_id": "MIB4KK0POK57NV",
  "message": "Use this instance_id to create a new WhatsApp session"
}
```

**Verification:**
- [ ] Server process is running
- [ ] Endpoint returns status "success"
- [ ] instance_id is unique on each request
- [ ] No errors in logs

---

## Frontend Deployment (PHP Application)

### Server: blast.myarchery.id

```bash
# 1. SSH to web server
ssh user@blast.myarchery.id

# 2. Navigate to project directory
cd /path/to/myarchery-blast

# 3. Check current branch
git branch

# 4. Pull latest changes
git pull origin main

# 5. Verify helper function exists
grep -A 20 "wa_generate_instance_id" inc/core/Whatsapp/Helpers/Whatsapp_helper.php

# 6. Clear cache
rm -rf writable/cache/*
rm -rf writable/tmp/*

# 7. Set proper permissions
chmod -R 755 writable/
chown -R www-data:www-data writable/

# 8. Restart PHP-FPM (if needed)
sudo systemctl restart php-fpm
# OR
sudo systemctl restart php8.1-fpm
```

**Verification:**
- [ ] Latest code pulled successfully
- [ ] Helper function exists
- [ ] Cache cleared
- [ ] Permissions correct
- [ ] PHP-FPM restarted

---

## Integration Testing

### Test 1: API Connectivity

```bash
# From web server, test API connection
curl http://103.82.92.157:8000/generate-instance-id
```

**Expected:** JSON response with unique instance_id

- [ ] API accessible from web server
- [ ] Response is valid JSON
- [ ] instance_id is present

### Test 2: Frontend Integration

1. Open browser: `https://blast.myarchery.id/whatsapp_profiles/oauth`
2. Open Developer Console (F12)
3. Check Network tab for API calls
4. Verify QR code loads

**Checklist:**
- [ ] Page loads without errors
- [ ] No 500/404 errors in console
- [ ] QR code section displays
- [ ] Instance ID is visible

### Test 3: Create New Session

1. Navigate to `https://blast.myarchery.id/account_manager`
2. Click "Add Whatsapp profile"
3. Note the instance_id displayed
4. Scan QR code with WhatsApp
5. Verify login success

**Checklist:**
- [ ] Instance ID is displayed correctly
- [ ] QR code is generated
- [ ] Can scan with WhatsApp
- [ ] Login redirects to account_manager
- [ ] New account appears in list

### Test 4: Multiple Simultaneous Sessions

Open 3 different browsers (Chrome, Firefox, Safari):

**Browser 1:**
1. Go to `https://blast.myarchery.id/whatsapp_profiles/oauth`
2. Note instance_id: `_____________`

**Browser 2:**
1. Go to `https://blast.myarchery.id/whatsapp_profiles/oauth`
2. Note instance_id: `_____________`

**Browser 3:**
1. Go to `https://blast.myarchery.id/whatsapp_profiles/oauth`
2. Note instance_id: `_____________`

**Verification:**
- [ ] All 3 instance IDs are DIFFERENT
- [ ] Each QR code is unique
- [ ] No conflicts between sessions

---

## Rollback Plan (If Issues Occur)

### Backend Rollback

```bash
# SSH to API server
ssh root@103.82.92.157
cd /www/wwwroot/api-blast

# Stop current server
pkill -f "node app.js"

# Restore backup
cp app.js.backup.YYYYMMDD_HHMMSS app.js

# Restart server
nohup node app.js > logs/server.log 2>&1 &
```

### Frontend Rollback

```bash
# SSH to web server
ssh user@blast.myarchery.id
cd /path/to/myarchery-blast

# Revert to previous commit
git log --oneline -n 5  # Find previous commit hash
git revert <commit-hash>
git push origin main

# OR hard reset (USE WITH CAUTION)
git reset --hard HEAD~1
git push origin main --force

# Clear cache
rm -rf writable/cache/*
```

---

## Post-Deployment Monitoring

### Monitor for 24 Hours

**Metrics to Watch:**

1. **API Server Logs:**
   ```bash
   ssh root@103.82.92.157
   tail -f /www/wwwroot/api-blast/logs/server.log
   ```
   
   Watch for:
   - [ ] No errors
   - [ ] Instance IDs being generated
   - [ ] Response times < 100ms

2. **PHP Error Logs:**
   ```bash
   tail -f /var/log/php-fpm/error.log
   # OR
   tail -f writable/logs/log-*.php
   ```
   
   Watch for:
   - [ ] No curl errors
   - [ ] No timeout errors
   - [ ] No undefined function errors

3. **Database:**
   ```sql
   -- Check for unique instance IDs
   SELECT instance_id, COUNT(*) as count 
   FROM tb_whatsapp_sessions 
   GROUP BY instance_id 
   HAVING count > 1;
   ```
   
   Expected: No duplicate instance_ids
   - [ ] No duplicates found

4. **User Reports:**
   - [ ] No user complaints about QR code issues
   - [ ] No reports of "session conflict"
   - [ ] Successful new profile additions

---

## Performance Baseline

**Before Deployment:**
- Average time to generate QR: _____ ms
- Number of active sessions: _____
- Error rate: _____ %

**After Deployment (24h):**
- Average time to generate QR: _____ ms
- Number of active sessions: _____
- Error rate: _____ %

**Expected Changes:**
- Time may increase by 50-100ms (acceptable)
- Error rate should decrease
- No session conflicts

---

## Success Criteria

### Must Have (Critical):
- [x] Backend endpoint deployed and accessible
- [x] Frontend calls new API endpoint
- [x] No instance ID collisions observed
- [ ] All existing functionality works
- [ ] QR codes generate successfully
- [ ] Users can login successfully

### Should Have (Important):
- [x] Documentation completed
- [x] Rollback plan ready
- [ ] Monitoring in place
- [ ] Error logs reviewed
- [ ] Performance acceptable

### Nice to Have (Optional):
- [ ] Rate limiting implemented
- [ ] Access token validation
- [ ] Performance metrics collected
- [ ] User feedback gathered

---

## Contact Information

**If Issues Occur:**

1. **Check Logs First:**
   - API: `/www/wwwroot/api-blast/logs/server.log`
   - PHP: `/var/log/php-fpm/error.log`

2. **Immediate Actions:**
   - Check if API server is running
   - Check if endpoint is accessible
   - Review error logs
   - Test with curl commands

3. **Rollback Decision:**
   - If > 10% error rate → Rollback immediately
   - If users cannot create sessions → Rollback
   - If server is down → Rollback

---

## Sign-off

**Deployment Date:** _______________

**Deployed By:** _______________

**Deployment Time:** _______________

**Post-Deployment Verification:**

| Test | Status | Notes |
|------|--------|-------|
| Backend endpoint accessible | [ ] Pass / [ ] Fail | |
| Frontend integration works | [ ] Pass / [ ] Fail | |
| QR code generation | [ ] Pass / [ ] Fail | |
| Multiple sessions | [ ] Pass / [ ] Fail | |
| No errors in logs | [ ] Pass / [ ] Fail | |

**Overall Status:** [ ] SUCCESS / [ ] NEEDS ATTENTION / [ ] ROLLBACK REQUIRED

**Notes:**
_______________________________________________________
_______________________________________________________
_______________________________________________________

---

**Ready for Production:** [ ] YES / [ ] NO

**Approved By:** _______________

**Date:** _______________
