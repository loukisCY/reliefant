import datetime
import smtplib
import sys


def send_mail():
    try:
        sender_email = "loukis500@gmail.com"
        password = "bxnrinloxxvbwvfn"
        subject = 'Reliefant Password Reset'

        rec_email = sys.argv[1]
        message_text = "This is your reset code: " + sys.argv[2] + "\n\nValid for 1 hour\n\nDO NOT SHARE"

        message = 'Subject: {}\n\n{}'.format(subject, message_text)

        server = smtplib.SMTP('smtp.gmail.com', 587)
        server.starttls()
        server.login(sender_email, password)
        server.sendmail(sender_email, rec_email, message)
        print('Mail sent ' + datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
    except Exception:
        print('Error trying to send mail')


if __name__ == '__main__':
    send_mail()