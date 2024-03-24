<?php
/**
 * Created by PhpStorm.
 * User: oleksii
 * Date: 17.01.19
 * Time: 22:45
 */

$html = <<<HTML
<div class="form-wrapper">
    <div class="form-title">
        <div class="title">טופס הצטרפות - עמיתים</div>
        <div class="description">
אני מבקש/ת להצטרף לארגון מנת"ה; הארגון הישראלי למנתחי התנהגות מוסמכים (ע"ר). 
מטרות הארגון, תקנון הארגון והקוד האתי כפי שמופיעים באתר האינטרנט של הארגון, ידועים ומוכרים לי.
			</div>
    </div>
    <form id="free-form"
              class="fellow"  
              name="free-form"
              action=""
              method="post">
              
        <input type="hidden" name="form_type" value="fellow">       

        <div class="field-wrapper space-between">
            <span class="required">
                <label for="last-name">שם משפחה:</label>
                <input id="last-name" type="text" name="lastname">
            </span>

            <span class="required">
                <label for="first-name">שם פרטי:</label>
                <input id="first-name" type="text" name="firstname">
            </span>

            <span>
                <label for="birth-date">ת. לידה:</label>
                <input id="birth-date" type="date" name="birth-date">
            </span>
        </div>

        <div class="field-wrapper space-between">
            <span>
                <label for="address">כתובת:</label>
                <input id="address" type="text" name="address">
            </span>

            <span class="required">
                <label for="cell-phone">טלפון נייד:</label>
                <input id="cell-phone" type="text" name="cell-phone">
            </span>

            <span>
                <label for="phone-number">טלפון נוסף:</label>
                <input id="phone-number" type="text" name="phone-number">
            </span>
        </div>

        <div class="field-wrapper space-between">
            <span class="user-email required">
                <label for="user-email">כתובת דואר אלקטרוני:</label>
                <input id="user-email" type="email" name="user-email">
            </span>
            
            <span class="required">
                <label for="id-number">ת.ז.:</label>
                <input id="id-number" type="text" name="id-number">
            </span>
        </div>

        <div class="field-wrapper">
            <label for="occupation">תחום עיסוק:</label>
            <input id="occupation" type="text" name="occupation">
        </div>
                
        <input type="submit" value="הרשמה">
    </form>

    <div class="form-footer">
       
    </div>
</div>
HTML;

echo $html;



