import boto3
client = boto3.client(
    'ses',
#     region_name='us-west-2',
#     region_name=region,
#     aws_access_key_id='AKIAXVBXIK5PNUURRSLS',
#     aws_secret_access_key='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'
)

from email.mime.text import MIMEText
from email.mime.application import MIMEApplication
from email.mime.multipart import MIMEMultipart
account = 'foundlingstheatrecompany'
email_address = 'ddouglass+TEST@ActiveCampaign.com'
msg = MIMEMultipart()
msg['Subject'] = f'Your export for {account} is done'
msg['From'] = 'ddouglass@ActiveCampaign.com'
msg['To'] = ', '.join(['ddouglass@ActiveCampaign.com', email_address])
# message body
part = MIMEText('<p> Test <p>     <h3>Who Am I?</h3>/n<img class="img-circle" src="http://foundlingstheatre.com/Aboutus/files/stacks_image_21.png" alt="DJ" width=200px>/n<h3>Alot of things</h3>', 'html')
msg.attach(part)
# attachment
# if attachment_string:   # if bytestring available
#     part = MIMEApplication(str.encode('attachment_string'))
# else:    # if file provided
part = MIMEApplication(open(f'/home/ubuntu/csvs/{account}_export.csv', 'rb').read())
part.add_header('Content-Disposition', 'attachment', filename='name_of_attachment.csv')
msg.attach(part)
response = client.send_raw_email(
    Source=msg['From'],
    Destinations=['ddouglass@ActiveCampaign.com', email_address],
    RawMessage={
        'Data': msg.as_string()
    }
)

def send_results(to):
	pass
	