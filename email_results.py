import boto3
from email.mime.text import MIMEText
from email.mime.application import MIMEApplication
from email.mime.multipart import MIMEMultipart

# client = boto3.client(
#     'ses',
# #     region_name='us-west-2',
# #     region_name=region,
# #     aws_access_key_id='XXXXXXXX',
# #     aws_secret_access_key='XXXXXXXX'
# )
# 
# 
account = 'foundlingstheatrecompany'
email_address = 'ddouglass+TEST@ActiveCampaign.com'
# 
# 
# 
# msg = MIMEMultipart()
# msg['Subject'] = f'Your export for {account} is done'
# msg['From'] = 'ddouglass@ActiveCampaign.com'
# msg['To'] = ', '.join(['ddouglass@ActiveCampaign.com', email_address])
# # message body
# part = MIMEText('<p> Test <p>     <h3>Who Am I?</h3>/n<img class="img-circle" src="http://foundlingstheatre.com/Aboutus/files/stacks_image_21.png" alt="DJ" width=200px>/n<h3>Alot of things</h3>', 'html')
# msg.attach(part)
# # attachment
# # if attachment_string:   # if bytestring available
# #     part = MIMEApplication(str.encode('attachment_string'))
# # else:    # if file provided
# part = MIMEApplication(open(f'/home/ubuntu/csvs/{account}_export.csv', 'rb').read())
# part.add_header('Content-Disposition', 'attachment', filename='name_of_attachment.csv')
# msg.attach(part)
# response = client.send_raw_email(
#     Source=msg['From'],
#     Destinations=['ddouglass@ActiveCampaign.com', email_address],
#     RawMessage={
#         'Data': msg.as_string()
#     }
# )



def send_results(to,start,end,total):
	client = boto3.client('ses')
	msg = MIMEMultipart()
	msg['Subject'] = f'Your export for {account} is done'
	msg['From'] = 'ddouglass@ActiveCampaign.com'
	msg['sender'] = 'ddouglass@ActiveCampaign.com'
# 	msg['To'] = ', '.join([to, 'ddouglass@ActiveCampaign.com'])
	msg['To'] = to
	msg['Cc'] = 'ddouglass@ActiveCampaign.com'
	# message body
	part = MIMEText(f'<html> <head></head><body><p> This started at {start} and finished at {end} taking a total time of {total} minutes<p>     <h3>Who Am I?</h3> <img  src="http://foundlingstheatre.com/Aboutus/files/stacks_image_21.png" alt="DJ" width=200px>/n<h3>Alot of things</h3></body></html>', 'html')
	msg.attach(part)
	part = MIMEApplication(open(f'/home/ubuntu/csvs/{account}_export.csv', 'rb').read())
	part.add_header('Content-Disposition', 'attachment', filename='name_of_attachment.csv')
	msg.attach(part)
# 	print(msg['To'])
# 	print(msg['From'])
# 	print(msg['Sender'])
# 	print(msg['Return-Path'])
# 	print(msg.as_string())
	response = client.send_raw_email(
 	    Source='ddouglass@ActiveCampaign.com',
# 	    Destinations=[to, 'ddouglass@ActiveCampaign.com'],
	    RawMessage={
	        'Data': msg.as_string()
	    }
	)

if __name__ == '__main__':	
	send_results('zblackcat@yahoo.com',23,24,25)
	