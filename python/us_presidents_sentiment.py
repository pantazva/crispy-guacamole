#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
@author: Vasilis Pantazopoulos | https://www.facebook.com/vasilis.pantazopoulos
"""

import os
os.environ["EAI_USERNAME"] = 'EXPERT.AI USERNAME'
os.environ["EAI_PASSWORD"] = 'EXPERT.AI PASSWORD'

import pandas as pd 
data = pd.read_csv("presidential_speeches.csv") 
data.head()

from expertai.nlapi.cloud.client import ExpertAiClient
client = ExpertAiClient()

import json

president_results = []
taxonomycat_categories = []

for ind in data.index:

    text = data['Transcript'][ind][0:5000] # expert.ai Free Tier Api Call and Daily Limit
    language= 'en'

    print(data['Date'][ind], data['President'][ind], data['Party'][ind])
    sentiment = client.specific_resource_analysis(
        body={"document": {"text": text}}, 
        params={'language': language, 'resource': 'sentiment'
    })

    # Output overall sentiment
    print("Output overall sentiment:")
    print(sentiment.sentiment.overall)
    
    taxonomy = 'emotional-traits'
    taxonomycat = client.classification(body={"document": {"text": text}}, 
                                    params={'taxonomy': taxonomy, 'language': language})
    
    print("Tab separated list of categories:")
    
    for category in taxonomycat.categories :
        # print(category.id_, category.hierarchy, sep="\t")
        # print(str(category.hierarchy))
        taxonomycat_categories.append(str(category.hierarchy[1]))
        

    president_results.append([data['Date'][ind], data['President'][ind], data['Party'][ind],data['Speech Title'][ind], 
                              sentiment.sentiment.overall, json.dumps(taxonomycat_categories)])
    taxonomycat_categories = []
    
president_results_pd = pd.DataFrame(president_results, columns=["Date", "President", "Party", "Speech Title",
                                                                "Sentiment", "Taxonomy"])
president_results_pd.to_csv('president_results.csv', index=True)
president_results_pd.groupby('President').agg({'Sentiment': 'mean'}).to_csv('president_mean_sentiment.csv', index=True)
president_results_pd.groupby('Party').agg({'Sentiment': 'mean'}).to_csv('party_mean_sentiment.csv', index=True)