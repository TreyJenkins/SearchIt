#!/usr/bin/python
import MySQLdb, sys, time

user = sys.argv[1]
passwd = sys.argv[2]

db = MySQLdb.connect(host="localhost", user=user, passwd=passwd, db="Search")

def clean(data):
    data = data.upper()
    data = data.replace("\t", " ").replace("\n", " ").replace("\r", " ").replace("\\", " ")
    data = data.replace(".", " ").replace(",", " ").replace("!", " ").replace("?", " ").replace(":", " ").replace(";", " ")
    data = data.replace("'", " ").replace('"', " ")
    data = data.split(" ")
    data = ' '.join(data).split()
    data = [ii for n,ii in enumerate(data) if ii not in data[:n]]
    return ' '.join(data)

infile = sys.argv[3]

indata = None

with open(infile, 'r') as f:
        indata = f.read()

output = {"tokens": str(clean(indata)), "timestamp": int(time.time()), "document": str(infile)}

x = db.cursor()

try:
    x.execute("INSERT INTO indexed VALUES (%s, %s, %s)",(str(clean(indata)), int(time.time()), str(infile)))
    print "Commiting..."
    db.commit()
    print "Wrote " + str(len(output["tokens"])) + " bytes of tokens"
except:
    print "Failed to ingest data"
    db.rollback()

db.close()
