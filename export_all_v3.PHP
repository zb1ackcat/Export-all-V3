<!-- https://www.youtube.com/watch?v=RTjd1nwvlj4 -->

<?php


import urllib.parse
import requests
import json
import csv
import time

offset = 0
count = 0
num_of_con = 0





function get_tags($url)
    response = requests.request("GET", url + "&api_key=" + api_key)
    x = json.loads(response.text)
    con_val=[]
    tag_str=''
    for fields in x['contactTags']:
        con_val.append(fields['tag'])
    #tag_str += tag_dict[k] for k in con_val if k in tag_dict
    for tag in con_val:
        if tag in tag_dict:
            tag_str += tag_dict[tag] + ","
    return tag_str



def get_field_values(url):
    time.sleep(.2)
    response = requests.request("GET", url + "&api_key=" + api_key)
    x = json.loads(response.text)
    con_val={}
    for fields in x['fieldValues']:
        con_val.update({fields['field']:fields['value']})
    return con_val





def make_header():
    d = {'ID':'', 'Email':'', 'First Name':'', 'Last Name':'', 'Phone':'', 'Creation Date':'','Orginization Name': ''}
    url = f"https://{account}.api-us1.com/api/3/fields/?api_key={api_key}"
    response = requests.request("GET", url)
    #print(response1)
    parsed = json.loads(response.text)
    fields = parsed['fields']
    for x in fields:
        d.update({x['title'] : ''})
    d.update({'Tags':[]})
    return d




def export_all_contacts():
    global count
    offset = count
    url = f"https://{account}.api-us1.com/api/3/contacts/?api_key={api_key}&offset={offset}&limit=100"
    time.sleep(.2)
    response = requests.request("GET", url)
    parsed = json.loads(response.text)
    contact_data = parsed['contacts']
    with open('/tmp/export.csv','a') as data:
        for contact in contact_data:
            contact_row = blank_dict
            csvwriter = csv.writer(data)
            if count == 0:
                x = make_header()
                header = x.keys()
                csvwriter.writerow(header)
            contact_row.update((k, v) for k, v in contact.items() if k in contact_row)
            #contact_row.update(contact)
            y = get_field_values(contact['links']['fieldValues'])
            contact_row.update(y)
            z = get_tags(contact['links']['contactTags'])
            contact_row.update({'tags':z})
            csvwriter.writerow(contact_row.values())
            count += 1
        wait_a_moment()





def wait_a_moment():
    offset = count
    print(f"Currently on {offset} out of {num_of_con} contacts")
    if offset < num_of_con: 
        export_all_contacts()
    else:
        end = time.time()
        total = (end - start)/60 
        print(f"Finish in {total} minutes")



def get_number_of_con():
    url = f"https://{account}.api-us1.com/api/3/contacts/?api_key={api_key}"
    response = requests.request("GET", url + "&api_key=" + api_key)
    x = json.loads(response.text)
    return int(x['meta']['total'])


# In[183]:


def make_dict():
    d = {'id':'', 'email':'', 'firstName':'', 'lastName':'', 'phone':'', 'cdate':'','orgname': ''}
    url = f"https://{account}.api-us1.com/api/3/fields/?api_key={api_key}"
    response = requests.request("GET", url)
    #print(response1)
    parsed = json.loads(response.text)
    fields = parsed['fields']
    for x in fields:
        d.update({x['id'] : ''})
    d.update({'tags':''})
    return d


# In[184]:


def make_tag_dict():
    url = f"https://{account}.api-us1.com/api/3/tags?api_key={api_key}"
    response = requests.request("GET", url)
    #print(response1)
    parsed = json.loads(response.text)
    tags = parsed['tags']
    d= {}
    for x in tags:
#MAYBE  int()
        d.update({x['id']:x['tag']})
    return d
    


# In[191]:


def start_contacts():
    global count
    global blank_dict
    global tag_dict
    global num_of_con
    global start
    start = time.time()
    tag_dict = make_tag_dict()
    blank_dict = make_dict()
    num_of_con = get_number_of_con()
    count= 0
    x = num_of_con * .015
    print(f'This will take about {x} minutes to complete')
    wait_a_moment()


# In[192]:


print('This script will export your contacts to a tmp/export.csv. To see this on a mac you will need to go to your hard drive and press CMD + Shift + "." to show hidden files. This takes aboout a minutes and a half per 100 contacts.')
x = input("What is the account? (The SOMETHNING ins SOMETHING.activehosted.com)")
account = x or "foundlingstheatrecompany"
x = input("What is the API Key?")
api_key= x


# In[193]:


start_contacts()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Export All Contacts with V3!</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
<form action="" method="get">
    <input type="text" name="account"/>
    <input type="text" name="api_key"/>
    <button type="submit">Submit</button>
</form>
<br/>
<div id="results" data-url="<?php if (!empty($url)) echo $url ?>">
    <?php
    if (!empty($array)) {
        foreach ($array['data'] as $key => $item) {
            echo '<img id="' . $item['id'] . '" src="' . $item['images']['low_resolution']['url'] . '" alt=""/><br/>';
        }
    }
    ?>
</div>
</body>
</html>
