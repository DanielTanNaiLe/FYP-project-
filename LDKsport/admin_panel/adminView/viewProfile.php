<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

   <style>
        .btn {
            background: #a370f0;
            color: white;
            font-family: inherit;
            padding: 0.35em;
            padding-left: 1.2em;
            font-size: 17px;
            font-weight: 500;
            border-radius: 0.9em;
            border: none;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            box-shadow: inset 0 0 1.6em -0.6em #714da6;
            overflow: hidden;
            position: relative;
            height: 2.8em;
            padding-right: 3.3em;
            cursor: pointer;
        }

        .btn .icon {
            background: white;
            margin-left: 1em;
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 2.2em;
            width: 2.2em;
            border-radius: 0.7em;
            box-shadow: 0.1em 0.1em 0.6em 0.2em #7b52b9;
            right: 0.3em;
            transition: all 0.3s;
        }

        .btn:hover .icon {
            width: calc(100% - 0.6em);
        }

        .btn .icon svg {
            width: 1.1em;
            transition: transform 0.3s;
            color: #7b52b9;
        }

        .btn:hover .icon svg {
            transform: translateX(0.1em);
        }

        .btn:active .icon {
            transform: scale(0.95);
        }

        .password-container {
            position: relative;
        }

        .password-container .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 2rem;
            color: #333;
        }

        .password-container .toggle-password:hover {
            color: #000;
        }

        .password-strength-label {
            font-size: 14px;
        }

        .password-strength {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>


<section class="form-container">
   <form action="" method="post">
      <h3>Update Profile</h3>
      <input type="hidden" name="prev_pass" value="<?= htmlspecialchars($fetch_profile['password']); ?>">
      <input type="text" name="name" value="<?= htmlspecialchars($fetch_profile['name']); ?>" required placeholder="Enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="email" name="email" value="<?= htmlspecialchars($fetch_profile['email']); ?>" required placeholder="Enter your email" maxlength="50" class="box">
      <div class="password-container">
         <input type="password" name="old_pass" id="old_pass" placeholder="Enter Old Password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('old_pass')"></i>
      </div>
      <div class="password-container">
         <input type="password" name="new_pass" id="new_pass" placeholder="Enter New Password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, ''); checkPasswordStrength(this.value);">
         <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('new_pass')"></i>
      </div>
      <div class="password-strength-label">Your Password: <span id="password_strength" class="password-strength"></span></div>
      <div class="password-container">
         <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm New Password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('confirm_pass')"></i>
      </div>
      <button class="cssbuttons-io-button btn" type="submit" name="submit" style="margin: 0 auto;">
         Update Now
         <div class="icon">
            <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
               <path d="M0 0h24v24H0z" fill="none"></path>
               <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" fill="currentColor"></path>
            </svg>
         </div>
      </button>
   </form>
</section>