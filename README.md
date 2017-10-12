# Scrap Job Ads from Online Job Board
This is an example and practice of how to use [Jobapis](https://github.com/jobapis/jobs-multi), an open source for integrating APIs from multiple online job board, to scrap job advertisements from these online job boards. 
I found there are some difficulties when I followed their toturial and examples (This is my first PHP project, so maybe it takes me more time to figure out everything). I also got some useful observation when I tried to scrap job ads from different job boards. Therefore, I would like to share my findings and my programs to those who also want to use [Jobapis](https://github.com/jobapis/jobs-multi) to scrap job ads.

*There are more examples on the github of [Jobapis/example](https://github.com/jobapis/jobs-multi/tree/master/example).*

## About
I included the source files for each job board directly in `index.php`. In `index.php`, you can set up the following main parameters:

- `$Source`: The name of job board that I scrap job ads from.
- `$SearchWord`: The keyword for job searching in given job board.
- `$pnum`: the page number that I start scrap job ads from. Sometimes, the scraping process would be interrupted, so I can start from the latest page number.

There are several and specific parameters for different platforms. For example, for Adzuna.com, parameters `$AdzuCate` and `$country` are mondatory.
For more detail please refer to *Supported APIs* in [Jobapis Github page](https://github.com/jobapis/jobs-multi). For each link listed in *Supported APIs*, the team of Jobapis described what parameters are mondatory for each job board.

## Supported Job Board in this Example

- [Github Jobs](https://jobs.github.com/)
- [Stack Overflow Jobs](https://stackoverflow.com/jobs)
- [Indeed](https://www.indeed.com)
- [Dice](https://www.dice.com/)
- [CareerCast](http://www.careercast.com/)
- [Adzuna](https://www.adzuna.com/)


## Infomation of Collected Job Ads

For the collected items of job ads, there are totally 36 items. These items can be categorized into:

- **Search Info**: Query, Source (job board), Post date, Url of job ads webpage. 
- **Company Info**: Company description, Company email, Company logo, Company name, Company url.
- **Location Info**: Country, City, Latitude, Longitude, Location, State, PostalCode.
- **Job Info**: Description, Salary (minimum and base salary), Requirments, Skills, Work hours... (totally 19 items)

Please note that not all the items are provided by all the job boards. Actually, the detailed items, like Requirments, Skills and Company description, are rarely provided by job boards.

Based on the job ads I collected in 2017, I listed what special items are provided by each job boards.
All the job board provide items in **Search Info**. 

- Github Jobs
- Stack Overflow Jobs
- Indeed
- Dice
- CareerCast
- Adzuna


## Usage
1. run `index.php`
2. the scraped files will be stroed in `result` folder
3. In `result` folder, the data will save in the folder named by `JobBoard_Month_Year`.
4. each file is named by the formate: `JobBoard_searchQuery_pageNum`. The file formate is json.


