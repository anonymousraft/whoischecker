# Bulk WHOIS Checker
## by Hitendra Singh Rathore
PHP based bulk WHOIS checker. It supports all the popular TLDs and ccTLDs. Upload a csv file that has the list of domains and extract the spreadsheet as the output. for the .com domains it works effortlessly.

Fixed the issue with .IN TLD. Now it also works with .IN TLDs.

## Check DNS Records
The tool is now able to check the DNS records for domains. You can easily check the A, AAAA, MX, NS, TXT or SOA record for any domain. 

While pointing to new server, it is essential that you carefully look the DNS propogation time. This feature will help you to determine the NS records stored on your ISPs DNS for the domain name you are checking. 

## Required Resources
1. Composer Installed. See [this](https://www.hostinger.in/tutorials/how-to-install-composer) guide to install composer
2. Open your command propmt or terminal, Navigate to the bulk whois checker folder and run command `composer install`
3. Local Server with PHP installed.
4. Make sure you are connected to the Internet.
5. Larger list of domains, Increase your PHP execution time to the max.

### In Case of Socket Error
If you have large domain list, you might come across the socket error. Socket error is simply due to the firewall rules implemented on the Whois Server.

In case of socket error, Please increase the value of `sleep_time` parameter in the file `checkwhois.php`

change the line `//'sleep_time' => 500000` to `'sleep_time' => 500000`

The default value is 250000 microseconds i.e. 0.25 Seconds. But you can increase as per your requirement.

`1 sec is equal to 1000000 micro seconds`

## Pull requests are welcome
if you find any issue, or if you any feature idea please create issue. Or if you feel that you can contribute to the project. I welcome pull requests. Make sure you describe the changes in detail.

This tool only extracts the general infomration about a TLDs available on port 43 of WHOIS server. No personal information like Name, Email can be extracted by this tool.

### Thank You.
