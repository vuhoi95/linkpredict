DATA = zeros(14,2,45);

count = 1;
for h = 1:9
    for k = h+1:10
        
        for j=1:14
            data = load([int2str(j+1991),'.txt']);
            DATA(j,1,count) = j+1991;
            DATA(j,2,count) = data(h,k);
        end
        count=count+1;
    end
end

data  = zeros(45,4);
data1 = load('forecast.txt');
for i=1:4
    x = DATA(i+10,2,:);
    data(:,i) = x;
end

mse = zeros(1,4);
for i=1:4
    mse(:,i) = sum((data(:,i) - data1(:,i)).*(data(:,i) - data1(:,i)))/45;
end

percent_year = zeros(1,4);
[n,m]=size(data);
count=0;
for i=1:n
    for j=1:m
        if data(i,j)>0
            data(i,j) = 1;
        end
        if data(i,j)<=0
            data(i,j) = 0;
        end
        
        if data1(i,j)>0
            data1(i,j) = 1;
        end
        if data1(i,j)<=0
            data1(i,j) = 0;
        end
        
        if data(i,j)==data1(i,j)
            count = count+1;
        end
    end
end
percent = (count/(n*m))*100;
for i=1:m
    count_year=0;
    for j=1:n
        if data(j,i)==data1(j,i)
            count_year = count_year+1;
        end
    end
    percent_year(:,i)=(count_year/n).*100;
end