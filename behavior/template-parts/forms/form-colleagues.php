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
        <div class="description" style="color:#000;">
אני מבקש/ת להצטרף לארגון מנת"ה; הארגון הישראלי למנתחי התנהגות מוסמכים (ע"ר). מטרות הארגון, תקנון הארגון והקוד האתי כפי שמופיעים באתר האינטרנט של הארגון, ידועים ומוכרים לי.
			</div>
    </div>
    <form id="pelepay-form"
              class="colleagues" 
              name="pelepayform"
              action="https://www.pelepay.co.il/pay/paypage.aspx"
              method="post"
              enctype="multipart/form-data">
              
        <input type="hidden" name="form_type" value="colleagues">      

        <div class="field-wrapper space-between">
            <span class="required">
                <label for="last-name">שם משפחה:</label>
                <input id="last-name" type="text" name="last-name">
            </span>

            <span class="required">
                <label for="first-name">שם פרטי:</label>
                <input id="first-name" type="text" name="first-name">
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
            <div class="block-name required">לימודי ניתוח התנהגות:</div>
            <div class="block-fields column">
                <p>אונ' תל אביב <input type="radio" name="accreditation" value="אונ' תל אביב"></p>
                <p>סמינר הקיבוצים <input type="radio" name="accreditation" value="סמינר הקיבוצים"></p>
                <p>(מכללת אור יהודה (2011-2013 <input type="radio" name="accreditation" value="(מכללת אור יהודה (2011-2013)"></p>
                <p>המכללה האקדמית בית ברל <input type="radio" name="accreditation" value="המכללה האקדמית בית ברל"></p>
				 <p>המכללה לחינוך על שם דוד ילין <input type="radio" name="accreditation" value="המכללה לחינוך על שם דוד ילין"></p>
                <p>האוניברסיטה העברית <input type="radio" name="accreditation" value="האוניברסיטה העברית"></p>
                <p>מכללת אורנים <input type="radio" name="accreditation" value="מכללת אורנים"></p>
                <p>מכללת כנרת <input type="radio" name="accreditation" value="מכללת כנרת"></p>
                <p><input id="other_accreditation" type="text" placeholder="הקלד כאן"> אחר <input type="radio" name="accreditation" value=""></p>
            </div>
        </div>

        <div class="field-wrapper">
            <label for="ba-in-the-field">תואר ראשון בתחום:</label>
            <input id="ba-in-the-field" type="text" name="ba-in-the-field">

            <label for="ba-academic-institution">מוסד אקדמי:</label>
            <input id="ba-academic-institution" type="text" name="ba-academic-institution">
        </div>

        <div class="field-wrapper">
            <label for="masters-degree-in-the-field">תואר שני בתחום:</label>
            <input id="masters-degree-in-the-field" type="text" name="masters-degree-in-the-field">

            <label for="masters-academic-institution">מוסד אקדמי:</label>
            <input id="masters-academic-institution" type="text" name="masters-academic-institution">
        </div>

        <div class="field-wrapper">
            <label for="additional-degree">תואר נוסף:</label>
            <input id="additional-degree" type="text" name="additional-degree">

            <label for="additional-academic-institution">מוסד אקדמי:</label>
            <input id="additional-academic-institution" type="text" name="additional-academic-institution">
        </div>
        
        <div class="field-wrapper required">
            <label for="finish-date">תאריך סיום לימודים:</label>
            <input id="finish-date" type="date" name="finish-date">
        </div>

        <div class="field-wrapper required">
            <label for="speciality">תחום התמחות:</label>
            <input id="speciality" type="text" name="speciality">
        </div>
        
        <div class="field-wrapper required">
            <label for="occupation">תחום עיסוק:</label>
            <input id="occupation" type="text" name="occupation">
        </div>
        
        <div class="field-wrapper">
            <label for="student-certificate">מצורף בזאת צילום תעודה ו/או אישורים המעידים על לימודי:</label>
            <input type="file" id="student-certificate" name="student-certificate[]" multiple>
        </div>
        
        <div class="field-wrapper">
            <label for="coupon">הכנס כאן קוד קופון:</label>
            <input id="coupon" type="text" name="coupon">
            <div class="apply-coupon">הפעל קופון</div>
            <div class="message"></div>
        </div>
        
        <input type="hidden" value="talishabat.bcba@gmail.com" name="business">
        <input type="hidden" id="pelepay-membership-amount" value="140" name="amount">
        <input type="hidden" id="pelepay-membership-type" value="עמיתים" name="description">
        <input type="hidden" value="" name="max_payments">
        <input type="hidden" id="pelepay-firstname" name="firstname" value=""/>
        <input type="hidden" id="pelepay-lastname" name="lastname" value=""/>
        <input type="hidden" id="pelepay-phone" name="phone" value=""/>
        <input type="hidden" id="pelepay-email" name="email" value=""/>
        <input type="hidden"
           value="https://www.behavior-analyst.co.il/create-new-account-form?payment_status=success"
           name="success_return">
        <input type="hidden"
           value="https://www.behavior-analyst.co.il/create-new-account-form?payment_status=fail"
           name="fail_return">
        <input type="hidden"
           value="https://www.behavior-analyst.co.il/create-new-account-form?payment_status=cancel"
           name="cancel_return">
        <div class="field-wrapper pelepay-btn">
           <div class="block-name">לתשלום 140:</div>
           <input id="pelepay-btn" type="image"
           src="http://www.pelepay.co.il/btn_images/pay_button_4.gif"
           name="submit"
           alt="Make payments with pelepay">
        </div>
        <p>לאחר קוד קופון <span class="newprice">99</span> ש"ח</p>
    </form>

    <div class="form-footer">

    </div>
</div>
HTML;

echo $html;



