CREATE VIEW DependentCounts AS (
    SELECT COUNT(dependent_ID) AS dependent_count, reservation_ID
    FROM IncludedDependents
    GROUP BY (reservation_ID)
);
