<div class="container-fluid div-center div-def-padding" style="background: #03A9F4;">
    <section>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <h1 style="color:white; font-weight:700;">Check DNS Records</h1>
                <hr />
                <h5>Check A, AA, NS, MX, TEXT and lot more records</h5>
                <p></p>
            </div>
        </div>
    </section>
</div>
<style>
    input[type=text] {
        all: unset;
    }

    input[type=submit] {
        all: unset;
    }

    /*Form style*/
    select {
        width: auto;
        background: white;
    }

    input[type=text] {
        width: auto;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #30a9f4;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        width: auto;
        background-color: #30a9f4;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color: #67c0f7;
    }
</style>
<div class="container-fluid div-def-padding">
    <div class="row h-100">
        <div class="col-md-12 col-lg-12 custom-border-12">
            <div class="card card-block w-25 custom-border-box">
                <form method="post" action="domainwhois.php">
                    <input type="text" name="domain_name" placeholder="Enter Domain Name or URL">
                    <select name="dns_record_type" id="dns_record">
                        <?php
                        foreach($this->dns_record_types as $types)
                        {
                            echo '<option value="'.$types.'">'.$types.'</option>';
                        }                      
                        ?>
                    </select>
                    <input type="submit" name="submit" value="Check Whois">
                </form>
            </div>
        </div>
    </div>